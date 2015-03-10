// Set the require.js configuration for your application.
require.config({
    map : {
        "handlebars" : {
            "underscore-string": "underscore.string"
        }
    },
    shim: {
        'underscore': {
            exports: '_'
        },
        'underscore.string': {
            deps: [
                'underscore'
            ]
        },
        'handlebars-orig': {
            exports: 'Handlebars'
        },
        'backbone': {
            deps: [
                'underscore',
                'underscore.string',
                'jquery'
            ],
            exports: 'Backbone'
        },
        'backbone-queryparams': {
            deps: [
                'backbone'
            ],
            exports: 'Backbone'
        },
        'backbone-validation': {
            deps: [
                'backbone'
            ],
            exports: 'Backbone'
        },
        'bootstrap': {
            deps: [
                'jquery'
            ]
        },
        'async': {
            exports: 'async'
        },
        'lightbox': ["jquery"],
        'jquery.form': ["jquery"]
    },

    // Libraries
    paths: {
        jquery: 'lib/jquery-1.11.2',
        'jquery.form': 'lib/jquery.form',
        underscore: 'lib/underscore',
        'underscore.string': 'lib/underscore.string',
        backbone: 'lib/backbone',
        lightbox: 'lib/lightbox',
        text: 'lib/text',
        i18n: 'lib/i18n',
        'bootstrap': 'lib/bootstrap.min',
        'handlebars-orig': 'lib/handlebars-v2.0.0',
        'handlebars': 'lib/handlebars-helpers',
        'backbone-queryparams': 'lib/backbone-queryparams',
        'backbone.validation': 'lib/backbone-validation',
        async: 'lib/async',
        hbs: 'lib/require-handlebars',
        moment: 'lib/moment',
        template: '../template',
		json2: 'lib/json2'
    }
});

// Load our app module and pass it to our definition function
require(['app']);
