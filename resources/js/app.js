import 'bootstrap'; // JS Bootstrap pour le menu hamburger
import '../css/app.css';
console.log("JS charge !");

import 'bootstrap/dist/js/bootstrap.bundle.min.js'; // JS Bootstrap
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

import tinymce from 'tinymce/tinymce';

// importer ce dont tu as besoin
import 'tinymce/icons/default';
import 'tinymce/themes/silver';
import 'tinymce/plugins/table';
import 'tinymce/plugins/image';
import 'tinymce/plugins/link';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/code';

tinymce.init({
  selector: 'textarea[name=content]',
  plugins: 'table image link lists code',
  toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright | bullist numlist | table | link image | code',
  menubar: false,
  height: 400,
});

