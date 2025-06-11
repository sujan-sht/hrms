<form action="/admin/update-employee-detail" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file">
    <br>
    <input type="submit" value="Submit">
</form>
