/**
 * Editor side of the Ledger Line blocks. No build step: plain JavaScript using WordPress's globals.
 * Text and figures are edited in the sidebar; the preview is drawn by the same PHP as the live site.
 */
( function ( wp ) {
	var el = wp.element.createElement;
	var __ = wp.i18n.__;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var useBlockProps = wp.blockEditor.useBlockProps;
	var PanelBody = wp.components.PanelBody;
	var TextControl = wp.components.TextControl;
	var TextareaControl = wp.components.TextareaControl;
	var SelectControl = wp.components.SelectControl;
	var Disabled = wp.components.Disabled;
	var ServerSideRender = wp.serverSideRender;
	var t = 'ledgerline-blocks';

	function preview( name, props ) {
		return el( Disabled, null, el( ServerSideRender, { block: name, attributes: props.attributes } ) );
	}

	function field( props, key, label, opts ) {
		opts = opts || {};
		var Control = opts.area ? TextareaControl : TextControl;
		return el( Control, {
			key: key,
			label: label,
			help: opts.help,
			type: opts.number ? 'number' : undefined,
			value: props.attributes[ key ],
			onChange: function ( v ) {
				var o = {};
				o[ key ] = opts.number ? ( parseFloat( v ) || 0 ) : v;
				props.setAttributes( o );
			},
		} );
	}

	function block( name, panels ) {
		wp.blocks.registerBlockType( name, {
			edit: function ( props ) {
				return el( 'div', useBlockProps(), el( InspectorControls, null, panels( props ) ), preview( name, props ) );
			},
			save: function () { return null; },
		} );
	}

	block( 'ledgerline/hero-panels', function ( p ) {
		return [
			el( PanelBody, { key: 1, title: __( 'Panel 1: receipts', t ) },
				field( p, 'month', __( 'Month', t ) ),
				field( p, 'receiptCount', __( 'Number of items', t ), { number: true } ),
				field( p, 'receiptsImage', __( 'Photo (file name in the theme, or a full image address)', t ) ),
				field( p, 'receiptsAlt', __( 'Photo description (alt text)', t ) ),
				field( p, 'chip1', __( 'Chip', t ) )
			),
			el( PanelBody, { key: 2, title: __( 'Panel 2: month-end checklist', t ), initialOpen: false },
				field( p, 'checklistTitle', __( 'Title', t ) ),
				field( p, 'checklist', __( 'Checklist lines', t ), { area: true, help: __( 'One per line.', t ) } ),
				field( p, 'doneLabel', __( 'Word next to each line', t ) ),
				field( p, 'statusTodo', __( 'Status while ticking', t ) ),
				field( p, 'statusDone', __( 'Status when done', t ) ),
				field( p, 'chip2', __( 'Chip', t ) )
			),
			el( PanelBody, { key: 3, title: __( 'Panel 3: profit and loss', t ), initialOpen: false },
				field( p, 'plTitle', __( 'Title', t ) ),
				field( p, 'period', __( 'Period', t ) ),
				field( p, 'income', __( 'Income ($)', t ), { number: true } ),
				field( p, 'cogs', __( 'Cost of goods sold ($)', t ), { number: true } ),
				field( p, 'opex', __( 'Operating expenses ($)', t ), { number: true, help: __( 'Gross and net profit are worked out from these three.', t ) } ),
				field( p, 'calcImage', __( 'Photo behind the sheet', t ) ),
				field( p, 'chip3', __( 'Chip', t ) )
			),
		];
	} );

	block( 'ledgerline/viz', function ( p ) {
		var type = p.attributes.type;
		return el( PanelBody, { title: __( 'Graphic', t ) },
			el( SelectControl, {
				label: __( 'Which graphic', t ),
				value: type,
				options: [
					{ label: __( 'Transactions getting categories', t ), value: 'transactions' },
					{ label: __( 'Estimated tax dates', t ), value: 'dates' },
					{ label: __( 'Cash-flow line', t ), value: 'flow' },
				],
				onChange: function ( v ) { p.setAttributes( { type: v } ); },
			} ),
			'transactions' === type && field( p, 'transactions', __( 'Transactions', t ), { area: true, help: __( 'One per line: name | amount | category', t ) } ),
			'dates' === type && field( p, 'dates', __( 'Dates', t ), { area: true, help: __( 'One per line, like "15 Apr".', t ) } ),
			'dates' === type && field( p, 'datesLabel', __( 'Label', t ) ),
			'flow' === type && field( p, 'flowLabel', __( 'Label', t ) )
		);
	} );

	block( 'ledgerline/book-form', function ( p ) {
		return [
			el( PanelBody, { key: 1, title: __( 'Fields', t ) },
				field( p, 'nameLabel', __( 'Name label', t ) ),
				field( p, 'emailLabel', __( 'Email label', t ) ),
				field( p, 'typeLabel', __( 'Business label', t ) ),
				field( p, 'choose', __( 'First choice (empty)', t ) ),
				field( p, 'types', __( 'Business types', t ), { area: true, help: __( 'One per line.', t ) } ),
				field( p, 'button', __( 'Button', t ) ),
				field( p, 'note', __( 'Note under the button', t ) )
			),
			el( PanelBody, { key: 2, title: __( 'Messages', t ), initialOpen: false },
				field( p, 'thanksTitle', __( 'Thank-you heading', t ) ),
				field( p, 'thanksText', __( 'Thank-you text', t ) ),
				field( p, 'errorText', __( 'Empty field message', t ) ),
				field( p, 'emailError', __( 'Wrong email message', t ) )
			),
		];
	} );
} )( window.wp );
