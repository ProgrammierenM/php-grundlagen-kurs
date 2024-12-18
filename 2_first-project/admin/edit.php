<?php
session_start();

require("../app/app.php");

ensure_user_is_authenticated();

$status = [];

if($_SERVER['REQUEST_METHOD'] === 'POST') {
  $org_game_name = sanitize('org_name', INPUT_POST);
  $game_name = sanitize('name', INPUT_POST);
  $game_genre = sanitize('genre', INPUT_POST);
  $game_description = sanitize('description', INPUT_POST);
  
  if(!$org_game_name || !$game_name || !$game_genre || !$game_description) {
    array_push($status, 'Angegebene Werte sind ungültig!');
  }
  else {
    edit_game($org_game_name, $game_name, $game_genre, $game_description);
    redirect('index.php');
  }
}

if($_SERVER['REQUEST_METHOD'] === 'GET') {
  $game = sanitize('game', INPUT_GET);

  if(!$game) {
    redirect("index.php");
  }

  $one_game = get_game($game);

  if(!$one_game) {
    view('404');
    die();
  }
}

$view_data = [
  'title' => "Game bearbeiten",
  'game' => $one_game,
  'status' => $status
];

view('admin/edit', $view_data);
