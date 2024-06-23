<h1>Add image</h1>

<form action="/upload" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label class="label" for="name">Photo name:</label>
        <input class="input" type="text" name="name" id="name">
    </div>
    <div class="form-group">
        <label class="label" for="photo">Choose photo:</label>
        <input class="input" type="file" name="photo" id="photo">
    </div>
    <div class="form-group">
        <input type="submit" value="Upload" name="submit">
    </div>
</form>