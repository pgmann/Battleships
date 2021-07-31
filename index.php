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
    <section class="py-5 container text-center">
      <div class="row py-lg-5">
        <div class="col-lg-6 col-md-8 mx-auto">
          <h1 class="fw-light">Battleships 🚢</h1>
          <p class="lead text-muted">Face off against your opponent and try to sink their ships before they sink yours!</p>
          <p>
            <button type="button" class="btn btn-primary my-2" data-bs-toggle="modal" data-bs-target="#createModal">Create</button>
            <button type="button" class="btn btn-secondary my-2" data-bs-toggle="modal" data-bs-target="#joinModal">Join</button>
          </p>
          <div class="modal fade" id="joinModal" tabindex="-1" aria-labelledby="joinModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="joinModalLabel">Join a game</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form class="needs-validation" id="joinForm" action="/play.php" novalidate>
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control" id="joinName" name="name" placeholder="Bob" autocomplete="given-name" required>
                      <label for="joinName" class="col-form-label">Name:</label>
                    </div>
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control" id="inviteCode" name="invite" placeholder="abcdef123" autocomplete="off" required>
                      <label for="inviteCode" class="col-form-label">Invite Code:</label>
                      <div id="inviteCodeError" class="invalid-feedback">Invite Code is required.</div>
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" form="joinForm" class="btn btn-primary">Join</button>
                </div>
              </div>
            </div>
          </div>
          <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="createModalLabel">Create a game</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form class="needs-validation" id="createForm" action="/play.php" novalidate>
                    <div class="form-floating mb-3">
                      <input type="text" class="form-control" id="createName" name="name" placeholder="Bob" autocomplete="given-name" required>
                      <label for="createName" class="col-form-label">Name:</label>
                    </div>
                    <div class="form-floating mb-3">
                      <input type="number" class="form-control" id="boardSize" name="size" min="5" max="10" placeholder="10" required>
                      <label for="boardSize" class="col-form-label">Board Size (5-10):</label>
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" form="createForm" class="btn btn-primary">Create</button>
                </div>
              </div>
            </div>
          </div>
          <div class="modal fade" id="waitingModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="waitingModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="waitingModalLabel">Waiting for opponent</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                  <div class="spinner-border text-primary my-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                  </div>
                  <p class="">
                    Your invite code is <b id="inviteCodeDisplay">generating</b>.
                  </p>
                  <p><small>Share this code with your opponent to allow them to join.</small></p>
                  <div id="inviteQR" class="d-flex justify-content-center"></div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="waitingAbort">Abort</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/qrcode_js@1.0.0/qrcode.min.js" integrity="sha256-xUHvBjJ4hahBW8qN9gceFBibSFUzbe9PNttUvehITzY=" crossorigin="anonymous"></script>
  <script src="/scripts/tooltips.js"></script>
  <script src="/scripts/lobby.js"></script>
</body>

</html>