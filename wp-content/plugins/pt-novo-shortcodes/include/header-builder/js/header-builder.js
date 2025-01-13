if (typeof PTHeaderBuilder === 'undefined') {
	function PTHeaderBuilder(el, options = {}) {
		this.$pageBuilder = jQuery(el)
		this.pageBuilderOffset = this.$pageBuilder.offset()
		this.$dropAreas = this.$pageBuilder.find('[data-col-type]')
		this.$elements = this.$pageBuilder.find('[data-parent-box]')
		this.$input = this.$pageBuilder.find('.header-builder-input')
		this.$deviceSwitcher = this.$pageBuilder.find('.screen-swither')
		this.isAddElem = false
		this.$currentDropBox = ''
		this.isDrag = false
		this.prevIndex = 0
		this.lastCoordinates = ''
		this.deviceType = 'desktop'

		this.createDraggable = (el) => {
			Draggable.create(el, {
				type: 'x,y',
				edgeResistance: 0.65,
				bounds: this.$pageBuilder,
				onClick: (e) => {
					if (e.target.classList == 'cross') {
						this.removeElement(jQuery(e.target).parents('.button-el'))
					} else {
						this.openSettingsPopup(jQuery(e.target))
					}
				},
				onDragStart: (e) => {
					this.isDrag = true
					this.$draggableElem = e.target
					this.parentBox = jQuery(e.target).attr('data-parent-box')
					this.lastCoordinates = ''

					if (this.parentBox == 'add-element') {
						this.isAddElem = true
						this.addedElem = jQuery(this.$draggableElem).clone()

						this.addedElem.insertAfter(jQuery(this.$draggableElem))
					} else {
						this.isAddElem = false
					}
				},
				onDrag: (e) => {
					this.getDropBox(e)
					this.changeOrder()
					this.calculatePositions()
				},
				onDragEnd: (e) => {
					this.isDrag = false
					this.getDropBox(e)

					this.$draggableElem = ''
					this.calculatePositions()

					if (this.isAddElem) {
						if (jQuery(e.target).attr('data-parent-box') == 'add-element') {
							this.addedElem.remove()
						} else {
							this.createDraggable(this.addedElem)
						}

						this.isAddElem = false
					}

					this.calculatePositions('init')
					this.hasBoxElements()

					this.updateInput()
				},
			});
		}

		this.changeOrder = () => {
			let buttonEls = this.$pageBuilder.find('[data-parent-box]'),
				parentBox = this.parentBox,
				rowButtons = ''

			if (this.$currentDropBox && this.$draggableElem && Draggable.hitTest(this.$draggableElem, this.$currentDropBox)) {
				parentBox = this.$currentDropBox.attr('data-col-type')
			}

			rowButtons = this.$pageBuilder.find('[data-parent-box="' + parentBox + '"]')

			this.prevIndex = rowButtons.index(jQuery(this.$draggableElem))

			rowButtons.each((index, elem) => {
				if (Draggable.hitTest(this.$draggableElem, elem) && this.$draggableElem != elem) {
					let insert = this.prevIndex > index ? 'before' : 'after'

					jQuery(elem)[insert](this.$draggableElem)
				}
			});

			jQuery(this.$draggableElem).attr('data-parent-box', parentBox)
		}

		this.hasBoxElements = () => {
			this.$dropAreas.each((index, drop) => {
				let colType = jQuery(drop).attr('data-col-type'),
					flag = false

				this.$elements.each((index, elem) => {
					if (colType == jQuery(elem).attr('data-parent-box')) {
						flag = true
						return;
					}
				});

				if (flag) {
					jQuery(drop).addClass('has-elements')
				} else {
					jQuery(drop).removeClass('has-elements')
				}
			});
		}

		this.getDropBox = (e) => {
			this.$currentDropBox = ''

			this.$dropAreas.each((index, elem) => {
				if (Draggable.hitTest(e.target, elem)) {
					this.$currentDropBox = jQuery(elem)
				}
			})
		}

		this.getBoxAlign = ($dropBox) => {
			let values = $dropBox.find('.edit-box').attr('data-values'),
			colType = $dropBox.attr('data-col-type'),
			align = 'left'

			if(typeof colType === 'undefined') return

			if(!values || values == '""' || values.length == 0) {
				colType.indexOf('right') != -1 && (align = 'right')
				colType.indexOf('center') != -1 && (align = 'center')
			} else {
				values = JSON.parse(values)

				align = typeof values.align !== 'undefined' && values.align
			}

			

			return align
		}

		this.getBoxZeroPoint = ($dropBox, $buttons) => {
			let boxOffset = $dropBox.offset(),
			width = $dropBox.width(),
			buttonsTotalWidth = 0,
			align = this.getBoxAlign($dropBox)
			x = boxOffset.left - this.pageBuilderOffset.left + parseInt($dropBox.css('padding-left')),
			y = boxOffset.top - this.pageBuilderOffset.top + parseInt($dropBox.css('padding-top'))

			$buttons.each((index, button) => {
				buttonsTotalWidth += jQuery(button).outerWidth(true)
			});

			if(align == 'right') {
				x = x + width - buttonsTotalWidth
			}

			if(align == 'center') {
				x = x + (width - buttonsTotalWidth) / 2
			}

			return {
				x,
				y
			}
		}

		this.calculatePositions = (type) => {
			if (typeof type === 'undefined') {
				type = 'default';
			}

			let $box = this.$dropAreas

			this.pageBuilderOffset = this.$pageBuilder.offset()

			if (type == 'init') {
				$box = this.$pageBuilder.find('.header-elements .wrap')
			}

			$box.each((index, dropBox) => {
				let $dropBox = jQuery(dropBox),
					boxType = $dropBox.attr('data-col-type')

					!boxType && (boxType = 'add-element')

				let $buttons = this.$pageBuilder.find('[data-parent-box="' + boxType + '"]')

				if ($buttons.length) {
					let coordinates = this.getBoxZeroPoint($dropBox, $buttons)

					$buttons.each((index, elem) => {
						let returnX = coordinates.x

						coordinates.x += jQuery(elem).outerWidth(true)

						if (this.$draggableElem == elem) return

						gsap.to(elem, type == 'deviseSwitch' ? 0 : .3, {
							x: returnX,
							y: coordinates.y,
						})
					});
				}
			});
		}

		this.resize = () => {
			this.pageBuilderOffset = this.$pageBuilder.offset()
		}

		this.getTransform = (elem) => {
			let style = window.getComputedStyle(elem),
				matrix = style.transform || style.webkitTransform || style.mozTransform,
				matrixValues = matrix.match(/matrix.*\((.+)\)/)[1].split(', ')

			this.lastCoordinates = {
				x: +matrixValues[4],
				y: +matrixValues[5]
			}
		}

		this.openSettingsPopup = ($elem) => {
			jQuery('body').css('overflow', 'hidden').append(`
				<div class="header-settings-popup-block">
					<div class="overlay"></div>
					<div class="wrap">
						<div class="cross">
							<i class="base-icon-close"></i>
						</div>
						<h3 class="title">
							<span class="circles"></span>
							<span class="title-label"></span>
						</h3>
						<div class="form-block">
							<div class="loader-spinner fas fa-circle-notch"></div>
						</div>
						<button class="submit-button">Save</button>
					</div>
				</div>
			`);

			let popupBlock = jQuery(document).find('.header-settings-popup-block');
			popupBlock.show().attr('data-fields', $elem.attr('data-fields'));
			popupBlock.find('h3.title .title-label').text($elem.attr('data-title') + ' Settings');

			jQuery.ajax({
				url: yprm_ajax.url,
				type: "POST",
				data: {
					action: 'get_header_form_fields',
					fields: $elem.attr('data-fields') && JSON.parse($elem.attr('data-fields')),
					values: $elem.attr('data-values') && JSON.parse($elem.attr('data-values'))
				},
				success: (data) => {
          console.log(data);
          
					popupBlock.addClass('loaded').find('.form-block').append(data);

					this.dependency(popupBlock);

					jQuery(document).trigger('opened-header-builder');
				}
			});

			popupBlock.on('click', '.wrap > .cross', () => {
				this.closeSettingsPopup();
			}).on('click', '.submit-button', () => {
				this.buildParams($elem, popupBlock);
				this.closeSettingsPopup();
				this.calculatePositions()
			})

			jQuery('body').on('keyup', (e) => {
				if (e.key === "Escape") {
					this.closeSettingsPopup();
				}
			});
		}

		this.closeSettingsPopup = () => {
			let popupBlock = jQuery(document).find('.header-settings-popup-block');

			jQuery('body').css('overflow', '')

			popupBlock.remove();
		}

		this.buildParams = (elem, popupBlock) => {
			let fields = popupBlock.find('.header-field-item'),
				paramsJSON = JSON.parse(elem.attr('data-fields'))
			resultParamsJSON = {};

			fields.each(function (index) {
				let param = jQuery(this),
				$input = param.find('[name]'),
				value = param.find('[name]').val();

				if ($input.attr('type') == 'checkbox') {
					let $span = $input.next().find('span')

					if($input.prop('checked')) {
						value = $span.eq(1).attr('data-value')
					} else {
						value = $span.eq(0).attr('data-value')
					}
				}

				if (typeof param.attr('data-param-value') !== 'undefined' && param.attr('data-param-value')) {
					resultParamsJSON[param.attr('data-param')] = param.attr('data-param-value');
					paramsJSON[index].value = param.attr('data-param-value');
				} else {
					resultParamsJSON[$input.attr('name')] = value;
					paramsJSON[index].value = value;
				}
			});

			resultParamsJSON = JSON.stringify(resultParamsJSON);
			paramsJSON = JSON.stringify(paramsJSON);

			elem.attr('data-values', resultParamsJSON);
			elem.attr('data-fields', paramsJSON);

			this.updateInput();
		}

		this.updateInput = () => {
			let resultJSON = {},
				hasEls = false;

			this.$pageBuilder.find('.edit-box').each((index, elem) => {
				let boxType = jQuery(elem).attr('data-box-type'),
					values = jQuery(elem).attr('data-values')

				
				if(values) {
					values = JSON.parse(values)
				}


				resultJSON[boxType] = {
					values: values,
					elements: []
				}
			});

			this.$pageBuilder.find('[data-parent-box]').each((index, elem) => {
				let boxType = jQuery(elem).attr('data-parent-box')

				if (boxType == 'add-element') return
				hasEls = true

				let buttonType = jQuery(elem).attr('data-header-element'),
					values = jQuery(elem).attr('data-values'),
					elemArray = {
						type: buttonType,
						values: JSON.parse(values)
					}

				resultJSON[boxType].elements.push(elemArray)
			});

			gsap.to(this.$pageBuilder.find('.clear-all'), {
				opacity: hasEls ? 1 : 0
			})

			this.$input.attr('value', JSON.stringify(resultJSON))
		}

		this.removeElement = (elem) => {
			elem.remove();
			this.updateInput();
			this.calculatePositions()
		}

		this.clearAll = () => {
			this.$pageBuilder.find('.button-el[data-parent-box!="add-element"]').each(function () {
				jQuery(this).remove();
			});

			this.updateInput()
		}

		this.dependency = (popupBlock) => {
			popupBlock.find('.header-field-item').each((index, elem) => {
				let $field = jQuery(elem),
					$input = $field.find('[name]'),
					func = () => {
						let depEls = popupBlock.find('[data-dependency-element="' + $input.attr('name') + '"]')

						this.dependencyTrigger(depEls, $input.val());
					}

				func()

				$field.on('change', '[name]', () => {
					func()
				})
			});
		}

		this.dependencyTrigger = (depEls, value) => {
			depEls.each((index, el) => {
				if (jQuery(el).attr('data-dependency-value') == value) {
					jQuery(el).show()
				} else {
					jQuery(el).hide()
				}
			});
		}

		this.toggleElementsVisibility = () => {
			this.$pageBuilder.find('[data-parent-box*="'+this.deviceType+'"]').removeClass('inactive')

			this.$pageBuilder.find('[data-parent-box]:not([data-parent-box*="'+this.deviceType+'"]):not([data-parent-box="add-element"])').addClass('inactive')
		}

		this.deviceSwitcher = () => {
			this.$deviceSwitcher.on('click', '.tab-button:not(.current)', (e) => {
				this.deviceType = jQuery(e.target).attr('data-type')
				jQuery(e.target).addClass('current').siblings().removeClass('current')
				
				this.$pageBuilder.find('.header-grid[data-screen-type="'+this.deviceType+'"]').addClass('current').siblings('.header-grid').removeClass('current')

				this.toggleElementsVisibility()
				this.calculatePositions('deviseSwitch')
			})
		}

		this.init = () => {
			this.calculatePositions('init')

			this.$elements.each((index, elem) => {
				this.createDraggable(elem)
			});

			this.toggleElementsVisibility()

			gsap.delayedCall(2, () => {
				gsap.to(this.$pageBuilder.find('[data-parent-box]'), {
					opacity: 1
				})
			})

			this.$pageBuilder.on('click', '.edit-box', (e) => {
				this.openSettingsPopup(jQuery(e.currentTarget))
			}).on('click', '.clear-all', (e) => {
				this.clearAll()
			})

			this.hasBoxElements()

			this.deviceSwitcher()
		}

		jQuery(window).on('load resize', () => {
			this.resize()
			this.calculatePositions()
		})

		this.init()
	}
}

jQuery('.yprm-header-builder-area').each(function () {
	new PTHeaderBuilder(jQuery(this));
})