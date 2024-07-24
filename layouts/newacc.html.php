<div class="d-flex justify-content-center align-items-center mt-5">
  <div class="card" style="width: 18rem;">
      <div class="card-body">
        <h5 class="card-title">Create account</h5>
          <form action="" method="post">
            <div class="mb-2">
              <input type="text" class="form-control" id="username" placeholder="Username" name="username">
            </div>
            <div class="mb-2">
              <input type="email" class="form-control" id="Email" placeholder="Email" name="email">
            </div>
            <div class="mb-2">
              <input type="text" class="form-control" id="Fname" placeholder="Full Name" name="name">
            </div>
            <div class="mb-1">
              <input type="password" class="form-control" id="Password" placeholder="Password" name="password"><br>
            </div>
            <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="role" id="flexRadioDefault1">
                <label class="form-check-label" for="flexRadioDefault1">
                  Admin
                </label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="role" id="flexRadioDefault2" checked>
                <label class="form-check-label" for="flexRadioDefault2">
                  Student
                </label>
              </div>
            <button type="submit" class="btn btn-primary mt-3">Submit</button>
          </form>
      </div>
  </div>
</div>