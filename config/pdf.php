<?php 
return [
  'custom_font_dir' => base_path('resources/fonts/'), // don't forget the trailing slash!
  'custom_font_data' => [
    'examplefont' => [
      'R'  => 'ExampleFont-Regular.ttf',    // regular font
      'B'  => 'ExampleFont-Bold.ttf',       // optional: bold font
      'I'  => 'ExampleFont-Italic.ttf',     // optional: italic font
      'BI' => 'ExampleFont-Bold-Italic.ttf' // optional: bold-italic font
    ],
    'mm3' => [
      'R' => 'Myanmar3.ttf',
    ]
    // ...add as many as you want.
  ]
];