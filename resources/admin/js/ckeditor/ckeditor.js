// ckeditor.js
import ClassicEditorBase from '@ckeditor/ckeditor5-editor-classic/src/classiceditor';
import EssentialsPlugin from '@ckeditor/ckeditor5-essentials/src/essentials';
import AutoformatPlugin from '@ckeditor/ckeditor5-autoformat/src/autoformat';
import BoldPlugin from '@ckeditor/ckeditor5-basic-styles/src/bold';
import ItalicPlugin from '@ckeditor/ckeditor5-basic-styles/src/italic';
import BlockQuotePlugin from '@ckeditor/ckeditor5-block-quote/src/blockquote';
import HeadingPlugin from '@ckeditor/ckeditor5-heading/src/heading';
import LinkPlugin from '@ckeditor/ckeditor5-link/src/link';
import ListPlugin from '@ckeditor/ckeditor5-list/src/list';
import ParagraphPlugin from '@ckeditor/ckeditor5-paragraph/src/paragraph';
import ImageInsert from '@ckeditor/ckeditor5-image/src/imageinsert';
import Image from '@ckeditor/ckeditor5-image/src/image';
import ImageToolbar from '@ckeditor/ckeditor5-image/src/imagetoolbar';
import ImageCaption from '@ckeditor/ckeditor5-image/src/imagecaption';
import ImageStyle from '@ckeditor/ckeditor5-image/src/imagestyle';
import ImageResize from '@ckeditor/ckeditor5-image/src/imageresize';
import LinkImage from '@ckeditor/ckeditor5-link/src/linkimage';
import ImageInline from '@ckeditor/ckeditor5-image/src/imageinline';
import TextAlign from '@ckeditor/ckeditor5-alignment/src/alignment';
//import SimpleUploadAdapter from './SimpleUploadImage';
import {Table, TableCellProperties, TableProperties, TableToolbar} from '@ckeditor/ckeditor5-table';

import RemoveFormat from '@ckeditor/ckeditor5-remove-format/src/removeformat';
import Font from '@ckeditor/ckeditor5-font/src/font';
import SourceEditing from '@ckeditor/ckeditor5-source-editing/src/sourceediting';

export default class ClassicEditor extends ClassicEditorBase {
}

ClassicEditor.builtinPlugins = [
    EssentialsPlugin,
    AutoformatPlugin,
    BoldPlugin,
    ItalicPlugin,
    BlockQuotePlugin,
    HeadingPlugin,
    LinkPlugin,
    ListPlugin,
    ParagraphPlugin,
    ImageInsert,
    Image,
    ImageToolbar,
    ImageCaption,
    ImageStyle,
    ImageResize,
    LinkImage,
    ImageInline,
    TextAlign,
    Table,
    TableToolbar,
    TableProperties,
    TableCellProperties,
    RemoveFormat,
    Font,
    SourceEditing,
];

ClassicEditor.defaultConfig = {
    toolbar: {
        items: [
            'heading',
            '|',
            'bold',
            'italic',
            'link',
            '|',
            'sourceEditing',
            '|',
            {
                label: 'Text style',
                icon: 'text',
                items: [
                    'FontColor',
                    'FontSize',
                    'fontBackgroundColor',
                ]
            },
            '|',
            /*            'imageStyle:inline',
                        "imageStyle:alignLeft",
                        "imageStyle:alignCenter",
                        "imageStyle:alignRight",
                        '|',
                        'toggleImageCaption',
                        'imageTextAlternative',*/
            '|',
            'insertTable',
            'alignment',
            'bulletedList',
            'numberedList',
            'blockQuote',
            'removeFormat',
            'undo',
            'redo'
        ]
    },
    image: {
        styles: [
            'alignLeft', 'alignCenter', 'alignRight'
        ],
        resizeOptions: [
            {
                name: 'resizeImage:original',
                value: null,
                icon: 'original'
            },
            {
                name: 'resizeImage:50',
                value: '50',
                icon: 'medium'
            },
            {
                name: 'resizeImage:75',
                value: '75',
                icon: 'large'
            }
        ],
        toolbar: [
            'imageStyle:alignLeft', 'imageStyle:alignCenter', 'imageStyle:alignRight',
            '|',
            'resizeImage',
            '|',
            'imageTextAlternative'
        ]
    },
    table: {
        contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells', 'tableProperties', 'tableCellProperties']
    },
    language: 'en'
};
