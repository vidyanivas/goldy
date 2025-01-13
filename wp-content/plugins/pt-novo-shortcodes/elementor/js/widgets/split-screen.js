class SplitScreenHandler extends elementorModules.frontend.handlers.Base {
	getDefaultSettings() {
		return {
			loop: this.getElementSettings('swiper_loop'),
			autoplay: this.getElementSettings('swiper_autoplay'),
			autoplay_timeout: this.getElementSettings('swiper_autoplay_speed'),
			arrows: this.getElementSettings('swiper_arrows'),
			categories_type: this.getElementSettings('categories_type'),
			categories_link: this.getElementSettings('categories_link'),
			categories_swiper_arrows: this.getElementSettings('categories_swiper_arrows'),
			swiper: '',
			cols: {
				xs: this.getElementSettings('categories_cols_xs'),
				sm: this.getElementSettings('categories_cols_sm'),
				md: this.getElementSettings('categories_cols_md'),
				lg: this.getElementSettings('categories_cols_lg'),
				xl: this.getElementSettings('categories_cols_xl'),
			},
			selectors: {
				area: '.banner-area',
				swiper: '.banner-slider',
				counterEl: '.counter-block .counter',
				prevEl: '.counter-block .prev',
				nextEl: '.counter-block .next',
				categories: '.categories-block',
				categoriesPrevEl: '.categories-block .prev',
				categoriesNextEl: '.categories-block .next',
			},
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings('selectors')

		return {
			$area: this.$element.find(selectors.area),
			$swiper: this.$element.find(selectors.swiper),
			$counterEl: this.$element.find(selectors.counterEl),
			$prevEl: this.$element.find(selectors.prevEl),
			$nextEl: this.$element.find(selectors.nextEl),
			$categories: this.$element.find(selectors.categories),
			$categoriesPrevEl: this.$element.find(selectors.categoriesPrevEl),
			$categoriesNextEl: this.$element.find(selectors.categoriesNextEl),
		}
	}

	onInit() {
    yprm_split_screen()
	}
}

jQuery(window).on('elementor/frontend/init', () => {
	const addHandler = ($element) => {
		elementorFrontend.elementsHandler.addHandler(SplitScreenHandler, {
			$element,
		});
	};

	elementorFrontend.hooks.addAction('frontend/element_ready/yprm_split_screen.default', addHandler);
});