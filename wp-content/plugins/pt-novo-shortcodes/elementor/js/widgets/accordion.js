/**
 * PT Novo Shortcodes - Addons for Novo Theme.
 *
 * @encoding     UTF-8
 * @version      4.0.25
 * @copyright    Copyright (C) 2011 - 2022 Promo Theme (https://promo-theme.com/). All rights reserved.
 * @license      Envato Standard Licenses (https://themeforest.net/licenses/standard)
 * @author       Promo Theme
 * @support      support@promo-theme.com
 **/

( function ( $ ) {
    "use strict";

    const ptAccordion = function ( $scope, $ ) {

        let wrapper = $scope.find( '.accordion-items' );

        if ( ! wrapper.length ) { return false; }

        if ( ! wrapper.find( '.item:first .top' ).hasClass( 'active' ) ) {

            setTimeout( function () {
                wrapper.find( '.item:first .top' ).trigger( 'click' );
            }, 3000 );

        }

        wrapper.find( '.item .top' ).on( 'click', function ( e ) {

            e.preventDefault();
            e.stopPropagation();

            let $tabTitle = $( this );

            /** Open. */
            if ( $tabTitle.closest( '.item' ).hasClass( 'active' ) ) {
                $tabTitle.addClass( 'elementor-active' );
            }

            /** Close. */
            else {
                $tabTitle.removeClass( 'elementor-active' );
            }

            $( window ).trigger( 'resize.px.parallax' ).trigger( 'resize' ).trigger( 'scroll' );
            setTimeout( function () {
                $( window ).trigger( 'resize.px.parallax' ).trigger( 'resize' ).trigger( 'scroll' );
            }, 300 );

        } );
    }

    /**
     * Register Accordion Widget Handler with Elementor after the main frontend instance has been initiated.
     **/
    $( window ).on( 'elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction( 'frontend/element_ready/yprm_accordion.default', ptAccordion );
    } );

} )( jQuery );
