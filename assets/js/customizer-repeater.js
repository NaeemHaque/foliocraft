/* FolioCraft Customizer repeater control. */
/* global jQuery, wp, fcRep */
( function ( $ ) {
	'use strict';

	window.fcRep = window.fcRep || { i18n: {} };
	var t = fcRep.i18n;

	function esc( s ) {
		return $( '<div>' ).text( s == null ? '' : String( s ) ).html();
	}
	function escAttr( s ) {
		return esc( s ).replace( /"/g, '&quot;' );
	}
	function parse( v ) {
		try {
			var a = JSON.parse( v );
			return Array.isArray( a ) ? a : [];
		} catch ( e ) {
			return [];
		}
	}

	function fieldHtml( field, value ) {
		var key = escAttr( field.key );
		var label = esc( field.label );
		var val = value == null ? '' : value;
		if ( field.type === 'textarea' ) {
			return '<label class="fc-rep-field"><span>' + label + '</span>' +
				'<textarea class="fc-rep-input" data-key="' + key + '" rows="3">' + esc( val ) + '</textarea></label>';
		}
		if ( field.type === 'media' ) {
			var has = val && parseInt( val, 10 ) > 0;
			return '<div class="fc-rep-field fc-rep-media" data-key="' + key + '">' +
				'<span>' + label + '</span>' +
				'<div class="fc-rep-media-preview"></div>' +
				'<input type="hidden" class="fc-rep-media-id" value="' + escAttr( val ) + '" />' +
				'<button type="button" class="button fc-rep-media-select">' + esc( has ? t.change : t.select ) + '</button> ' +
				'<button type="button" class="button-link fc-rep-media-remove"' + ( has ? '' : ' style="display:none"' ) + '>' + esc( t.remove ) + '</button>' +
				'</div>';
		}
		var type = field.type === 'url' ? 'url' : 'text';
		return '<label class="fc-rep-field"><span>' + label + '</span>' +
			'<input type="' + type + '" class="fc-rep-input" data-key="' + key + '" value="' + escAttr( val ) + '" /></label>';
	}

	function rowHtml( fields, item ) {
		item = item || {};
		var body = '';
		$.each( fields, function ( i, f ) {
			body += fieldHtml( f, item[ f.key ] );
		} );
		return '<div class="fc-rep-row">' +
			'<div class="fc-rep-row-head">' +
				'<span class="fc-rep-handle dashicons dashicons-menu" title="' + escAttr( t.drag ) + '"></span>' +
				'<span class="fc-rep-row-title">' + ( item.title ? esc( item.title ) : esc( t.untitled ) ) + '</span>' +
				'<button type="button" class="button-link fc-rep-remove" title="' + escAttr( t.remove ) + '"><span class="dashicons dashicons-trash"></span></button>' +
				'<span class="fc-rep-toggle dashicons dashicons-arrow-down-alt2"></span>' +
			'</div>' +
			'<div class="fc-rep-row-body">' + body + '</div>' +
		'</div>';
	}

	function setPreview( $m, att ) {
		var url = att.sizes && att.sizes.thumbnail ? att.sizes.thumbnail.url : att.url;
		$m.find( '.fc-rep-media-preview' ).html( '<img src="' + escAttr( url ) + '" alt="" />' );
	}
	function renderMediaPreview( $m ) {
		var id = parseInt( $m.find( '.fc-rep-media-id' ).val(), 10 );
		if ( ! id ) { return; }
		wp.media.attachment( id ).fetch().done( function () {
			setPreview( $m, wp.media.attachment( id ).toJSON() );
		} );
	}

	function init( $wrap ) {
		var fields = $wrap.data( 'fields' );
		if ( typeof fields === 'string' ) {
			try { fields = JSON.parse( fields ); } catch ( e ) { fields = []; }
		}
		var $input = $wrap.find( '.fc-rep-value' );
		var $rows = $wrap.find( '.fc-rep-rows' );

		$.each( parse( $input.val() ), function ( i, item ) {
			$rows.append( rowHtml( fields, item ) );
		} );
		$rows.find( '.fc-rep-media' ).each( function () { renderMediaPreview( $( this ) ); } );

		function sync() {
			var out = [];
			$rows.children( '.fc-rep-row' ).each( function () {
				var $r = $( this ), o = {};
				$r.find( '.fc-rep-input' ).each( function () { o[ $( this ).data( 'key' ) ] = $( this ).val(); } );
				$r.find( '.fc-rep-media' ).each( function () { o[ $( this ).data( 'key' ) ] = $( this ).find( '.fc-rep-media-id' ).val(); } );
				out.push( o );
			} );
			$input.val( JSON.stringify( out ) ).trigger( 'change' );
		}

		$wrap.on( 'click', '.fc-rep-add', function ( e ) {
			e.preventDefault();
			$rows.append( rowHtml( fields, {} ) );
			$rows.children( '.fc-rep-row' ).last().addClass( 'open' );
			sync();
		} );
		$wrap.on( 'click', '.fc-rep-remove', function ( e ) {
			e.preventDefault();
			$( this ).closest( '.fc-rep-row' ).remove();
			sync();
		} );
		$wrap.on( 'click', '.fc-rep-row-head', function ( e ) {
			if ( $( e.target ).closest( '.fc-rep-remove, .fc-rep-handle' ).length ) { return; }
			$( this ).closest( '.fc-rep-row' ).toggleClass( 'open' );
		} );
		$wrap.on( 'input change', '.fc-rep-input', function () {
			if ( $( this ).data( 'key' ) === 'title' ) {
				$( this ).closest( '.fc-rep-row' ).find( '.fc-rep-row-title' ).text( $( this ).val() || t.untitled );
			}
			sync();
		} );
		$wrap.on( 'click', '.fc-rep-media-select', function ( e ) {
			e.preventDefault();
			var $m = $( this ).closest( '.fc-rep-media' );
			var frame = wp.media( { title: t.select, library: { type: 'image' }, multiple: false, button: { text: t.use } } );
			frame.on( 'select', function () {
				var att = frame.state().get( 'selection' ).first().toJSON();
				$m.find( '.fc-rep-media-id' ).val( att.id );
				setPreview( $m, att );
				$m.find( '.fc-rep-media-remove' ).show();
				sync();
			} );
			frame.open();
		} );
		$wrap.on( 'click', '.fc-rep-media-remove', function ( e ) {
			e.preventDefault();
			var $m = $( this ).closest( '.fc-rep-media' );
			$m.find( '.fc-rep-media-id' ).val( '' );
			$m.find( '.fc-rep-media-preview' ).empty();
			$( this ).hide();
			sync();
		} );

		$rows.sortable( { handle: '.fc-rep-handle', items: '> .fc-rep-row', update: sync } );
	}

	wp.customize.bind( 'ready', function () {
		$( '.fc-rep' ).each( function () { init( $( this ) ); } );
	} );
} )( jQuery );
