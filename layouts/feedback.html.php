<div class="row-auto">
    <form action="" method="post" enctype="multipart/form-data">
        <div class="col-sm-4 mt-5 ms-5 mb-2">
            <input type="text" class="form-control" placeholder = "Feedback Title" name="mtitle" id="qtitle">
        </div>
        <div class="col-sm-4 ms-5">
            <textarea class="form-control" id="qcont" name="mcont" rows="3" placeholder = "Feedback Content"></textarea>
        </div>
        <button type="submit" class="btn btn-primary col-sm-1 ms-5 mt-2"><?=isset($qsubmit) ? $qsubmit : 'Submit';?></button>
    </form>
</div>