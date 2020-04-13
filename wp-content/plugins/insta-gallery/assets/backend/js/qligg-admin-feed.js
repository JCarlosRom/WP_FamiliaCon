(function ($) {
  var count = 0,
          timer;

  var is_blocked = function ($node) {
    return $node.is('.processing') || $node.parents('.processing').length;
  };

  var block = function () {
    $('#qligg_modal').addClass('processing');
  };

  var unblock = function () {
    $('#qligg_modal').removeClass('processing');
  };

  _.mixin({
    escapeHtml: function (attribute) {
      return attribute.replace('&amp;', /&/g)
              .replace(/&gt;/g, ">")
              .replace(/&lt;/g, "<")
              .replace(/&quot;/g, '"')
              .replace(/&#039;/g, "'");
    },
    getFormData: function ($form) {
      return $form.serializeJSON({checkboxUncheckedValue: 'false', parseBooleans: true, parseNulls: true});
      //breakes image highlight id
//      return $form.serializeJSON({checkboxUncheckedValue: 'false', parseBooleans: true, parseNumbers: true, parseNulls: true});
    }
  });

  // Model
  // ---------------------------------------------------------------------------

  var Feed = Backbone.Model.extend({
    defaults: qligg_feed.args
  });

  var FeedViewTabs = Backbone.View.extend({
    templates: {},
    initialize: function () {
      this.templates.window = wp.template('qligg-modal-tabs');
    },
    render: function () {
      this.model.attributes.panel = 'tab_panel_feed';
      this.$el.html(this.templates.window(this.model.attributes));
    }
  });

  var FeedViewPanels = Backbone.View.extend({
    templates: {},
    initialize: function () {
      this.templates.window = wp.template('qligg-modal-panels');

    },
    render: function () {
      this.$el.html(this.templates.window(this.model.attributes));
      this.$el.trigger('qligg-enhanced-color');
    }
  });

  var FeedView = Backbone.View.extend({
    events: {
      'change input': 'enable',
      'change textarea': 'enable',
      'change select': 'enable',
      'click .media-modal-image': 'setLayout',
      'click .media-modal-backdrop': 'close',
      'click .media-modal-close': 'close',
      'click .media-modal-prev': 'edit',
      'click .media-modal-next': 'edit',
      'click .media-modal-tab': 'tab',
      'change .media-modal-render-tabs': 'renderTabs',
      'change .media-modal-render-panels': 'renderPanels',
      'submit .media-modal-form': 'submit',
      'qligg.color.change input': 'enable',
    },
    templates: {},
    initialize: function () {
      _.bindAll(this, 'open', 'tab', 'edit', 'change', 'load', 'render', 'close', 'submit');
      this.init();
      this.open();
    },
    init: function () {
      this.templates.window = wp.template('qligg-modal-main');
    },
    assign: function (view, selector) {
      view.setElement(this.$(selector)).render();
    },
    render: function () {
      var modal = this;
      modal.$el.html(modal.templates.window(modal.model.attributes));
      this.tabs = new FeedViewTabs({model: modal.model});
      this.panels = new FeedViewPanels({model: modal.model});
      this.assign(this.tabs, '#qligg-modal-tabs');
      this.assign(this.panels, '#qligg-modal-panels');
      _.delay(function () {
        modal.$el.trigger('qligg-enhanced-color');
      }, 100);
    },
    load: function () {
      var modal = this,
              $modal = modal.$el.find('#qligg_modal');
      if (modal.model.attributes.id == undefined) {
        modal.render();
        return;
      }
      $.ajax({
        url: ajaxurl,
        data: {
          action: 'qligg_edit_feed',
          nonce: qligg_feed.nonce.qligg_edit_feed,
          feed_id: this.model.attributes.id
        },
        dataType: 'json',
        type: 'POST',
        beforeSend: function () {
          //if ($modal.length)
          //    block();
        },
        complete: function () {
          // if ($modal.length)
          unblock();
        },
        error: function () {
          alert('Error!');
        },
        success: function (response) {
          if (response.success) {
            modal.model.set(response.data);
            modal.render();
          } else {
            alert(response.data);
          }
        }
      });
    },
    edit: function (e) {
      e.preventDefault();
      var modal = this,
              $button = $(e.target),
              feed_count = parseInt($('#qligg_feeds_table tr[data-feed_id]').length),
              order = parseInt(modal.model.get('order'));
      count++;
      if (timer) {
        clearTimeout(timer);
      }

      timer = setTimeout(function () {

        if ($button.hasClass('media-modal-next')) {
          order = Math.min(order + count, feed_count);
        } else {
          order = Math.max(order - count, 1);
        }
        modal.model.set({
          id: parseInt($('#qligg_feeds_table tr[data-feed_position=' + order + ']').data('feed_id'))
//          id: parseInt($('#qligg_feeds_table tr[data-item_nonce=' + order + ']').data('item_id'))
        });
        count = 0;
        modal.load();
      }, 300);
    },
    open: function (e) {
      var modal = this;
      $('body').addClass('modal-open').append(this.$el);
      if (modal.model.attributes.id == undefined) {
        _.delay(function () {
          unblock();
          modal.setUsername();
        }, 100);

        return;
      }
      this.load();
    },
    setLayout: function (e) {
      e.preventDefault();
      e.stopPropagation();
      $(e.target).find('input[type=radio]').prop('checked', true);//.trigger('change');
      $(e.target).siblings().find('input[type=radio]').prop('checked', false);
      this.updateModel(e);
      this.renderPanels(e);
      this.renderTabs(e);
      this.enable(e);
    },
    setUsername: function (e) {
      var modal = this,
              $select = modal.$el.find('#qligg_modal').find('form').find('select[name=username]');
      $select.trigger('change');
    },
    updateModel: function (e) {
      e.preventDefault();
      var modal = this,
              $form = modal.$el.find('#qligg_modal').find('form');

      var model = _.getFormData($form);

      this.model.set(model);
    },
    tab: function (e) {
      e.preventDefault();

      var modal = this,
              $modal = modal.$el.find('#qligg_modal'),
              $tab = $(e.currentTarget),
              $tabs = $modal.find('ul.qligg-tabs'),
              panel = $tab.find('a').attr('href').replace('#', '');

      $tabs.find('.active').removeClass('active');
      $tab.addClass('active');

      this.model.attributes['panel'] = panel;
      this.model.changed['panel'] = panel;

      this.renderPanels(e);

    },
    renderTabs: function (e) {
      this.renderPanels(e);
      this.tabs.render();
    },
    renderPanels: function (e) {
      this.updateModel(e);
      this.panels.render();
    },
    change: function (e) {
      e.preventDefault();
      this.updateModel(e);
    },
    reload: function (e) {
      if (this.$el.find('#qligg_modal').hasClass('reload')) {
        location.reload();
        return;
      }
      this.remove();
      return;
    },
    close: function (e) {
      e.preventDefault();
      this.undelegateEvents();
      $(document).off('focusin');
      $('body').removeClass('modal-open');
      // if necesary reload... 
      this.$el.find('#qligg_modal').addClass('reload');
      this.reload(e);
      return;
    },
    enable: function (e) {
      $('.media-modal-submit').removeProp('disabled');
      this.updateModel(e);

    },
    submit: function (e) {
      e.preventDefault();
      var modal = this,
              $modal = modal.$el.find('#qligg_modal'),
              $spinner = $modal.find('.settings-save-status .spinner'),
              $saved = $modal.find('.settings-save-status .saved');
      $.ajax({
        url: ajaxurl,
        data: {
          action: 'qligg_save_feed',
          nonce: qligg_feed.nonce.qligg_save_feed,
          feed: JSON.stringify(modal.model.attributes)
        },
        dataType: 'json',
        type: 'POST',
        beforeSend: function () {
          $('.media-modal-submit').prop('disabled', true);
          $spinner.addClass('is-active');
        },
        complete: function () {
          $saved.addClass('is-active');
          $spinner.removeClass('is-active');
          _.delay(function () {
            $saved.removeClass('is-active');
          }, 1000);
        },
        error: function (response) {
          alert('Error!');
        },
        success: function (response) {
          console.log(response);
          if (response.success) {

            if (modal.model.attributes.id == undefined) {
              $modal.addClass('reload');
              modal.reload(e);
              modal.close(e);
            }

          } else {
            alert(response.data);
          }
        }
      });
      return false;
    }
  });

  var FeedModal = Backbone.View.extend({
    initialize: function (e) {
      var $button = $(e.target),
              feed_id = $button.closest('[data-feed_position]').data('feed_id');
      var model = new Feed();
      model.set({
        id: feed_id
      });
      new FeedView({
        model: model
      }).render();
    }
  });

  $(document).on('qligg-enhanced-color', function (e) {

    $('.color-picker').filter(':not(.enhanced)').each(function () {

      if ($(this).is('[readonly]')) {
        $(this).parent('.form-field').addClass('disabled-color-picker');
      }

      $(this).wpColorPicker({
        change: function (event, ui) {
          console.log('wpColorPicker');
          $(event.target).trigger('qligg.color.change');
        },
        clear: function (event, ui) {
//          $(event.target).trigger('change');
        },
        hide: function (event, ui) {
          aler('!!!!');
//          $(event.target).trigger('change');
        }
      });
    });

  });

  // Add feed
  // ---------------------------------------------------------------------------

  $('#qligg-add-feed').on('click', function (e) {
    e.preventDefault();
    new FeedModal(e);
  });

  // Edit feed
  // ---------------------------------------------------------------------------

  var exist_modal = false;
  $('.qligg_edit_feed').on('click', function (e) {
    e.preventDefault();
    if (!exist_modal) {
      new FeedModal(e);
      exist_modal = true;
    }
  });

  // Delete feed
  // ---------------------------------------------------------------------------

  $('.qligg_delete_feed').on('click', function (e) {
    e.preventDefault();

    var c = confirm(qligg_feed.message.confirm_delete);

    if (!c) {
      return false;
    }

    var $button = $(e.target),
            $spinner = $button.parent().find('.spinner'),
            feed_id = $button.closest('[data-feed_id]').data('feed_id');

    $.ajax({
      url: ajaxurl,
      data: {
        action: 'qligg_delete_feed',
        nonce: qligg_feed.nonce.qligg_delete_feed,
        feed_id: feed_id
      },
      dataType: 'json',
      type: 'POST',
      beforeSend: function () {
        $spinner.addClass('is-active');
      },
      complete: function () {
        $spinner.removeClass('is-active');
      },
      error: function (response) {
      },
      success: function (response) {

        if (response.data) {
          console.log(response.data);
          location.reload();
        } else {
          alert(response.data);
        }
      }
    });

  });

  // Feed clear cache
  // ---------------------------------------------------------------------------

  $('.qligg_clear_cache').on('click', function (e) {
    e.preventDefault();

    var c = confirm(qligg_feed.message.confirm_clear_cache);

    if (!c) {
      return false;
    }

    var $button = $(e.target),
            $spinner = $button.parent().find('.spinner'),
            feed_id = $button.closest('[data-feed_id]').data('feed_id');

    $.ajax({
      url: ajaxurl,
      type: 'post',
      data: {
        action: 'qligg_clear_cache',
        feed_id: feed_id,
        nonce: qligg_feed.nonce.qligg_clear_cache,
      },
      beforeSend: function () {
        $spinner.addClass('is-active');
      },
      success: function (response) {
        if (response.success) {
          setTimeout(function () {
            $spinner.removeClass('is-active');
          }, 300);
        } else {
          alert(response.data);
        }
      },
      complete: function () {
        setTimeout(function () {
          $spinner.removeClass('is-active');
        }, 600);
      },
      error: function (jqXHR, textStatus) {
        console.log(textStatus);
      },
    });
  });

  // Copy shortcode
  // ---------------------------------------------------------------------------

  $(document).on('click', '[data-qligg-copy-feed-shortcode]', function (e) {
    e.preventDefault();
    $($(this).data('qligg-copy-feed-shortcode')).select();
    document.execCommand('copy');
  });

})(jQuery);