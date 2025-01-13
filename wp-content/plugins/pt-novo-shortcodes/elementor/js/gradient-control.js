jQuery(window).on('elementor:init', () => {
	var gradientItemView = elementor.modules.controls.BaseMultiple.extend({

		ui: function () {
			var ui = elementor.modules.controls.BaseMultiple.prototype.ui.apply(this, arguments)

			ui.pickerButton = '.gradient-button';
			ui.pickerClear = '.clear-button'

			return ui;
		},

		events: function () {
			return _.extend(elementor.modules.controls.BaseMultiple.prototype.events.apply(this, arguments), {
				'click @ui.pickerButton': 'onClickButton',
				'click @ui.pickerClear': 'onClearPicker',
			});
		},

		onClickButton: function (event) {
			let $pickerGradient = jQuery('.gpikr-elementor-control-default-' + this.model.cid)

			$pickerGradient.toggleClass('opened')
			$pickerGradient.css({
				top: this.$el.offset().top,
				left: this.$el.outerWidth() + 5
			});

			this.initGpickr()
		},

		onClearPicker: function (event) {
			let $pickerGradient = jQuery('.gpikr-elementor-control-default-' + this.model.cid)

			this.value = {
				stops: [],
				linearAngle: '',
				radialPosition: '',
				gradient: ''
			}

			$pickerGradient.removeClass('opened')
			this.ui.pickerClear.removeClass('visibility')
			this.ui.pickerButton.find('div').css('background-image', '')

			this.saveValue();
		},

		initGpickr: function () {
			if(this.gpickr) return

			let value = this.getCurrentValue(),
			hasGradient = value && typeof value.stops !== 'undefined' && value.stops.length > 1,
				stops = [
					['#000', 0],
					['#ccc', 1]
				]

			if (hasGradient) {
				let currentStops = []
				value.stops.forEach(function (v, i) {
					currentStops.push([v.color, v.location])
				})

				if(currentStops.length > 1) {
					stops = currentStops
				}
			}

			this.gpickr = new GPickr({
				el: '.gpikr-elementor-control-default-' + this.model.cid + ' div',
				stops: stops
			})

			this.gpickr.on('init', () => {
				if (value && typeof value.linearAngle !== 'undefined') {
					this.gpickr.setLinearAngle(value.linearAngle)
				}

				if (value && typeof value.radialPosition !== 'undefined') {
					this.gpickr.setRadialPosition(value.radialPosition)
				}

			}).on('change', () => {
				let result = {
					stops: this.gpickr.getStops(),
					linearAngle: this.gpickr.getLinearAngle(),
					radialPosition: this.gpickr.getRadialPosition(),
					gradient: this.gpickr.getGradient(),
				}

				this.ui.pickerButton.find('div').css('background-image', this.gpickr.getGradient())

				this.value = result

				this.saveValue();
			})
		},

		initClearPicker: function() {
			let value = this.getCurrentValue()

			if(typeof this.value !== 'undefined' && this.value) {
				value = this.value
			}

			if (value && typeof value.stops !== 'undefined' && value.stops.length > 1) {
				this.ui.pickerClear.addClass('visibility')
			} else {
				this.ui.pickerClear.removeClass('visibility')
			}
		},

		createDOM: function () {
			jQuery('<div class="gpikr-elementor-control-default-' + this.model.cid + ' yprm-gradient-editor-gpickr"><div></div></div>').appendTo(jQuery(document.body));
		},

		setButtonGradient: function() {
			let value = this.getCurrentValue()

			if(value && typeof value.stops !== 'undefined' && value.stops.length > 1) {
				this.ui.pickerButton.find('div').css('background-image', value.gradient)
			}
		},

		onReady: function () {
			this.value = {}

			this.createDOM()
			this.initClearPicker()
			this.setButtonGradient()
			this.hasValue()
			this.outsideClick()
		},

		saveValue: function () {
			if(typeof this.value.stops !== 'undefined' && this.value.stops.length < 2) {
				this.value = {
					stops: [],
					linearAngle: '',
					radialPosition: '',
					gradient: ''
				}
			}
			
			jQuery.each(this.value, (index, value) => {
				this.setValue(index, value)
			}); 

			this.initClearPicker()
			
			this.triggerMethod( 'value:type:change' )
		},

		hasValue: function() {
			if(this.ui.pickerButton.find('div').css('background-image')) {
				this.ui.pickerButton.prev('.clear-button').addClass('visibility')
			}
		},

		onBeforeDestroy: function () {
			$pickerGradient = jQuery('.gpikr-elementor-control-default-' + this.model.cid)

			$pickerGradient.removeClass('opened').queue((next) => {
				$pickerGradient.get(0).remove()

				next()
			})
			this.saveValue();
		},

		outsideClick: function(event) {
			jQuery(document).on('click', (e) => {
				if(jQuery(e.target).closest('.gpikr-elementor-control-default-' + this.model.cid).length == 0 && jQuery(e.target).closest('.gradient-button').length == 0) {
					jQuery('.gpikr-elementor-control-default-' + this.model.cid).removeClass('opened')
				}
			});
		},

		destroy: function() {
			this.onBeforeDestroy()
		}
	});

	elementor.addControlView('gradient', gradientItemView);
})