<link href="style/title.css" rel="stylesheet">
<div class="row-auto">
    <form action="" method="post">
        <div class="col-sm-4 ms-5 mb-2">
            <textarea placeholder = "Your answer" class="form-control" name="comment" id="comment" rows="3" <?=isset($_SESSION['userid']) ? "" : "disabled";?>></textarea>
        </div>
        <button type="submit" class="btn btn-primary col-sm-1 ms-5" <?=isset($_SESSION['userid']) ? "" : 'disabled';?>>Submit</button>
    </form>
</div>