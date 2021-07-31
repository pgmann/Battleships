<?php

class Point {
  public $x, $y;

  public function __construct(int $x, int $y) {
    $this->x = $x;
    $this->y = $y;
  }

  public function __toString() {
    return $this->x . "," . $this->y;
  }
}

class Ship {
  public $name;
  public $x, $y, $size;
  public $vertical;
  public $hits = array();

  public function __construct(string $name, int $x, int $y, int $size, bool $vertical) {
    $this->name = $name;
    $this->x = $x;
    $this->y = $y;
    $this->size = $size;
    $this->vertical = $vertical;
  }

  public function get_all_points(): array {
    $points = array();
    $currentX = $this->x;
    $currentY = $this->y;
    for ($i = 0; $i < $this->size; $i++) {
      $points[] = new Point($currentX, $currentY);
      if ($this->vertical) {
        $currentY++;
      } else {
        $currentX++;
      }
    }
    return $points;
  }

  public function get_hits(): array {
    return $this->hits;
  }

  public function is_hit(Point $point): bool {
    $hit = in_array($point, $this->get_all_points());
    if ($hit && !in_array($point, $this->hits)) $this->hits[] = $point;
    return $hit;
  }

  public function is_sunk(): bool {
    return count($this->hits) >= $this->size;
  }

  public function intersects(Ship $ship) {
    $common_points = array_intersect($this->get_all_points(), $ship->get_all_points());
    return count($common_points) > 0;
  }
}

class Board {
  public $name;
  public $my_turn = false;
  public $ships = array();
  public $misses = array();
  public $last_strike;
  public $size;

  public function __construct(int $size) {
    $this->size = $size;
    $this->place_ship("Carrier", 5);
    $this->place_ship("Battleship", 4);
    $this->place_ship("Cruiser", 3);
    $this->place_ship("Submarine", 3);
    $this->place_ship("Destroyer", 2);
  }

  public function can_place(Ship $to_place_ship): bool {
    foreach ($this->ships as $ship) {
      if ($ship->intersects($to_place_ship)) return false;
    }
    return true;
  }

  public function place_ship($name, $size) {
    do {
      $vertical = rand(0, 1) > 0;
      $x = rand(0, $this->size - ($vertical ? 1 : $size));
      $y = rand(0, $this->size - ($vertical ? $size : 1));
      $ship = new Ship($name, $x, $y, $size, $vertical);
    } while (!$this->can_place($ship));
    $this->ships[] = $ship;
  }

  public function get_hits(): array {
    $hits = array();
    foreach ($this->ships as $ship) {
      $hits = array_merge($hits, $ship->get_hits());
    }
    return $hits;
  }

  public function get_misses(): array {
    return $this->misses;
  }

  public function strike(Point $point): bool {
    foreach ($this->ships as $ship) {
      if ($ship->is_hit($point)) {
        $this->last_strike = array(
          "point" => $point,
          "name" => $ship->name
        );
        return true;
      }
    }
    if (!in_array($point, $this->misses)) $this->misses[] = $point;
    $this->last_strike = array(
      "point" => $point,
      "name" => null
    );
    return false;
  }

  public function is_alive(): bool {
    foreach ($this->ships as $ship) {
      if (!$ship->is_sunk()) return true;
    }
    return false;
  }
}

function fail_with($error_msg) {
  echo json_encode(array("error" => $error_msg));
  exit();
}

function end_session($delete = true) {
  // load session
  session_start();

  // delete session data from server
  if ($delete) $_SESSION = array();

  // delete session id from client
  if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
  }

  // also delete player cookie
  setcookie("player", '', time() - 42000);

  // unload
  if ($delete) session_destroy();
}
