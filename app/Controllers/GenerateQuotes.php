<?php

namespace App\Controllers;

class GenerateQuotes extends BaseController
{
  public function index()
  {
    $data = [
      "title" => "Generate Quotes"
    ];
    return view('content/generate_quotes', $data);
  }
}
