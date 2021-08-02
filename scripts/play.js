var letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];

function get_display_coord(x, y) {
  return letters[x] + (y + 1);
}

var state, waiter;

// STATE
function updateState(data) {
  if (data.error) {
    if (["The game has ended", "Invalid invite code"].includes(data.error)) window.location.href = "./";
    alert("ERROR: " + data.error);
    return;
  }

  state = data;

  // update display
  console.log("State update:", state);
  opponentFleetName.innerText = state.opponent;
  if (myBoard.innerHTML === "") {
    // first draw - create rows and cells
    for (var i = 0; i < state.my_board.size; i++) {
      var row = document.createElement("div");
      for (var j = 0; j < state.my_board.size; j++) {
        var cell = document.createElement("div");
        row.appendChild(cell);
      }
      myBoard.appendChild(row);
    }
    for (var i = 0; i < state.my_board.size; i++) {
      var row = document.createElement("div");
      for (var j = 0; j < state.my_board.size; j++) {
        var cell = document.createElement("div");
        row.appendChild(cell);
      }
      opponentBoard.appendChild(row);
    }
  }

  // mark ships and hits on my grid
  for (let ship of state.my_board.ships) {
    let x = ship.x;
    let y = ship.y;
    let sunk = ship.hits.length >= ship.size;
    for (var i = 0; i < ship.size; i++) {
      var cell = myBoard.children[y].children[x];
      cell.dataset.title = "<b>" + get_display_coord(x, y) + " - " + ship.name + "</b><br>" + (sunk ? "Fully sunk" : ship.hits.length + " hit" + (ship.hits.length == 1 ? "" : "s"));
      cell.classList.add("ship");
      if (ship.hits.find(elem => elem.x == x && elem.y == y) != null) cell.classList.add("hit");

      if (ship.vertical) y++;
      else x++;
    }
  }
  // mark misses on my grid
  for (let coord of state.my_board.misses) {
    var cell = myBoard.children[coord.y].children[coord.x];
    cell.dataset.title = "<b>" + get_display_coord(coord.x, coord.y) + " - Miss</b><br>An enemy strike landed here without hitting your ships."
    cell.classList.add("miss");
  }

  // mark my hits and misses on opponent grid
  let y = 0;
  for (let row of opponentBoard.children) {
    let x = 0;
    for (let cell of row.children) {
      let isMiss = state.my_misses.find(elem => elem.x == x && elem.y == y) != null;
      let isHit = state.my_hits.find(elem => elem.x == x && elem.y == y) != null;

      cell.dataset.title = "<b>" + get_display_coord(x, y) + "</b><br>" + (isMiss ? "You missed" : isHit ? "You hit!" : state.my_board.my_turn ? "Click to strike" : "You can strike here on your turn");
      if (isHit) cell.classList.add("hit");
      if (isMiss) cell.classList.add("miss");
      (function (x, y) {
        cell.onclick = isMiss || isHit || !state.my_board.my_turn ? null : () => strike(x, y);
      })(x, y);
      x++;
    }
    y++;
  }
  updateTooltip();

  // check win and turn status
  if (state.winner != null) {
    // someone won since this is now set to true/false
    $(".board-wrapper").removeClass("turn");
    gameStatus.innerText = state.winner ? "You win!" : "You lost :(";
  } else if (state.my_board.my_turn) {
    // it's now this player's turn
    $(".board-wrapper")[0].classList.remove("turn");
    $(".board-wrapper")[1].classList.add("turn");
    boards.classList.remove("toggled");
    var lastStrike = state.my_board.last_strike;
    var lastMove = lastStrike == null ? "First Move" : lastStrike.name ? "Your " + lastStrike.name + " was hit at " + get_display_coord(lastStrike.point.x, lastStrike.point.y) : "The enemy tried " + get_display_coord(lastStrike.point.x, lastStrike.point.y) + " and missed.";
    gameStatus.innerHTML = lastMove + "<br><b>It's your turn!</b><br>Click a square in <span></span>'s fleet to strike it.";
    gameStatus.lastElementChild.innerText = state.opponent;
  } else {
    // it's now the opponent's turn
    $(".board-wrapper")[0].classList.add("turn");
    $(".board-wrapper")[1].classList.remove("turn");
    boards.classList.remove("toggled");
    var lastMove = state.my_last_strike ? "You hit the enemy's ship!<br>" : "";
    gameStatus.innerHTML = lastMove + "It's <b></b>'s turn, please wait...";
    gameStatus.lastElementChild.innerText = state.opponent;
    if (!waiter) {
      // keep asking the server if it's my turn again yet
      waiter = window.setInterval(() => {
        $.get("data.php", data => {
          if (data.error) updateState(data);
          if (data.my_board.my_turn || data.winner != null) {
            window.clearInterval(waiter);
            waiter = null;
            updateState(data);
          }
        });
      }, 1000);
    }
  }
}

// execute a move
function strike(x, y) {
  $.get("data.php?x=" + x + "&y=" + y, data => {
    updateState(data);
  });
}

// lets the user switch which board is visible on mobile
boardToggle.addEventListener("click", () => {
  $(boards).toggleClass("toggled");
});

// lets the user end the game
endGame.addEventListener("click", () => {
  if (waiter) window.clearInterval(waiter);
  $.get("data.php?end", data => {
    window.location.href = "./";
  });
});

// init page with data
$.get("data.php", data => {
  updateState(data);
});