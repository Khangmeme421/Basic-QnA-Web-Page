<div class="row-auto">
    <form action="" method="post" enctype="multipart/form-data">
        <div class="col-sm-4 mt-5 ms-5 mb-2">
            <input type="text" class="form-control" placeholder = "Question Title" name="qtitle" id="qtitle" required value="<?=isset($qtitle) ? $qtitle : '';?>">
        </div>
        <div class="col-sm-3 ms-5 mb-3">
            <select class="form-select" id="subject" name="subject">
                <option selected>Choose a subject</option>
                <?=$sub?>
            </select>
        </div>
        <div class="col-sm-3 ms-5 mb-3">
            <input type="file" class="form-control" id="inputGroupFile02" accept="image/*" name ="img">
        </div>
        <div class="col-sm-4 ms-5">
            <textarea class="form-control" id="qcont" name="qcont" rows="3" placeholder = "Question Content"><?=isset($qcontent) ? $qcontent : '';?></textarea>
        </div>
        <button type="submit" class="btn btn-primary col-sm-1 ms-5 mt-2"><?=isset($qsubmit) ? $qsubmit : 'Submit';?></button>
    </form>
</div>