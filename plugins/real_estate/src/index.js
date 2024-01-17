import { registerBlockType } from '@wordpress/blocks';
import { useEffect, useState } from '@wordpress/element';

registerBlockType('real-estate/filter', {
    title: 'Фильтр Недвижимости',
    icon: 'admin-home',
    category: 'widgets',

    edit: ({ attributes, setAttributes }) => {
        const [shortcodeContent, setShortcodeContent] = useState('');

        useEffect(() => { 
            fetch('/wp-json/real-estate/v1/shortcode') // в зависимости от локального сервера возможно придется поменять
                .then(response => response.text())
                .then(data => setShortcodeContent(data));
        }, []);

        return (
            <div dangerouslySetInnerHTML={{ __html: shortcodeContent }} />
        );
    },

    save: () => {
        return null;
    },
});
