import { useEffect, useState } from "@wordpress/element";
import { getSetting } from '@germanized/settings';
import { __, sprintf } from '@wordpress/i18n';

/**
 * External dependencies
 */
const Block = ({
   text,
   title,
   children,
   checkoutExtensionData,
   extensions,
   cart
}) => {
    const [ show, setShow ] = useState(false );
    const gzdData = extensions.hasOwnProperty( 'woocommerce-germanized' ) ? extensions['woocommerce-germanized'] : {};
    const applies = gzdData['applies_for_photovoltaic_system_vat_exempt'];

    useEffect( () => {
        if ( applies ) {
            setShow( true );
        } else {
            setShow( false );
        }
    }, [
        applies,
        setShow
    ] );

    if ( ! show ) {
        return null;
    }

    const currentText = text || sprintf( __( 'To benefit from the tax exemption, please confirm the VAT exemption according to <a href="%s" target="_blank">§12 paragraph 3 UStG</a> by activating the checkbox.', 'woocommerce-germanized' ), 'https://www.gesetze-im-internet.de/ustg_1980/__12.html' );
    const currentTitle = title || __( 'Your shopping cart is eligible for VAT exemption', 'woocommerce-germanized' );

    return (
		<div className="wc-gzd-block-checkout__photovoltaic-system-notice wc-block-components-notice-banner is-info">
            <h2
                className="wc-block-components-title"
                dangerouslySetInnerHTML={ {
                    __html: currentTitle,
                } }
            />
            <p
                dangerouslySetInnerHTML={ {
                    __html: currentText,
                } }
            />
		</div>
	);
};

export default Block;
