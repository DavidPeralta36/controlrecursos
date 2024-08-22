const mix = require('laravel-mix');
const webpack = require('webpack');

mix.js('resources/js/app.js', 'public/js')
   .vue() // Asegúrate de tener esto para que Laravel Mix compile archivos Vue
   .webpackConfig({
       plugins: [
           new webpack.DefinePlugin({
               __VUE_OPTIONS_API__: true, // Habilita la API de opciones de Vue 3
               __VUE_PROD_DEVTOOLS__: false, // Deshabilita las Devtools en producción
               __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: false // Elimina la advertencia
           })
       ]
   })
   .sass('resources/sass/app.scss', 'public/css');

if (mix.inProduction()) {
   mix.version();
}