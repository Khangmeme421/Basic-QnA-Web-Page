<div class="row-auto">
    <form action="" method="post" enctype="multipart/form-data">
        <div class="col-sm-4 mt-5 ms-5 mb-2">
            <input type="text" class="form-control" placeholder = "Enter module name" name="module" id="module" required <?=isset($val) ?$val : '';?>>
        </div>
        <button type="submit" class="btn btn-primary col-sm-1 ms-5 mt-2">Submit</button>
    </form>
</div>
