<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Battleships</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link href="/css/styles.css" rel="stylesheet">
</head>

<body>
  <header>
    <div class="navbar navbar-dark bg-dark shadow-sm">
      <div class="container">
        <b class="navbar-brand">🚢 Battleships</b>
      </div>
    </div>
  </header>
  <main>
    <section class="py-3 py-md-5 container text-center">
      <div id="boards" class="row">
        <div class="board-wrapper col-lg-6">
          <h3 class="fleet-name">My Fleet</h3>
          <div id="myBoard" class="board"></div>
        </div>
        <div class="board-wrapper col-lg-6">
          <h3 class="fleet-name"><span id="opponentFleetName">Opponent</span>'s Fleet</h3>
          <div id="opponentBoard" class="board"></div>
        </div>
      </div>
      <div class="row">
        <div class="mt-3">
          <p id="gameStatus">Loading...</p>
          <button type="button" id="boardToggle" class="btn btn-secondary">Toggle Boards</button>
          <button type="button" id="endGame" class="btn btn-danger">End Game</button>
        </div>
      </div>
    </section>

  </main>

  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script src="/scripts/tooltips.js"></script>
  <script src="/scripts/play.js"></script>
</body>

</html>