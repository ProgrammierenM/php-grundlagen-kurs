<?php
function get_all_games() {
  $filename = CONFIG['filename'];
  $json = '';

  if(!file_exists($filename)) {
    file_put_contents($filename, '');
  }
  else {
    $json = file_get_contents($filename);
  }

  return json_decode($json);
}

function get_game($game) {
  $all_games = get_all_games();
  foreach($all_games as $list_item) {
    if($list_item->id == $game) {
      return $list_item;
    }
  }

  return false;
}

function search_games($search) {
  $all_games = get_all_games();

  $result = array_filter($all_games, function($item) use($search) {
    return 
      stripos($item->name, $search) !== false ||
      stripos($item->genre, $search) !== false || 
      stripos($item->description, $search) !== false;
  });

  return $result;
}

function add_game($name, $genre, $description) {
  $game_list = get_all_games();

  $id = uniqid();

  $new_game = new Game($id, $name, $genre, $description);
  array_push($game_list, $new_game);
  
  /* array_push($game_list, [
    "id" => $id,
    "name" => $name,
    "genre" => $genre,
    "description" => $description
  ]); */

  set_all_games($game_list);
}

function edit_game($id, $name, $genre, $description) {
  $all_games = get_all_games();

  foreach($all_games as $list_item) {
    if($list_item->id == $id) {
      $list_item->name = $name;
      $list_item->genre = $genre;
      $list_item->description = $description;
    }
  }

  set_all_games($all_games);
}

function delete_game($id) {
  $all_games = get_all_games();

  foreach($all_games as $key => $list_item) {
    if($list_item->id == $id) {
      //unset($all_games[$key]);
      array_splice($all_games, $key, 1);
      break;
    }
  }

  set_all_games($all_games);
}

function set_all_games($game_list) {
  $filename = CONFIG['filename'];
  $json = json_encode($game_list);
  file_put_contents($filename, $json);
}