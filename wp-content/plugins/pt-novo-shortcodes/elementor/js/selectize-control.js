var selectizeItemView = elementor.modules.controls.BaseData.extend({
  onReady: function () {
    this.$el.find('select').selectize({
      plugins: ['remove_button', 'drag_drop'],
      //items: ['<?php echo wp_kses($css_option, 'post') ?>']
    });
  },

  saveValue: function () {
    //this.setValue(this.ui.textarea[0].emojioneArea.getText());
  },

  onBeforeDestroy: function () {
  }
});

elementor.addControlView('selectize', selectizeItemView);