<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>RPC Bulacan ID</title>
    <meta name="description" content="RPC Bulacan ID">
  </head>
  <body>
      <div style="border: 1px solid #aaa; width: 765px; height: 485px; background-image: url(https://devsite.rpcbulacan.org/images/leni.png)">
          <div style="text-transform:uppercase; font-size: 12px; width: 210px; position: absolute; top: 376px; left: 155px;">{{ $name }}</div>
          <div style="font-size: 13px; width: 210px; position: absolute; top: 407px; left: 155px;">{{ $id }}</div>
          <div style="position: absolute; top: 378px; left: 20px;">
              <img src="{{ $img }}" width="{{ $qrcode_size }}" height="{{ $qrcode_size }}">
          </div>
          <div style="text-transform:uppercase; width: 210px; font-size: 12px; position: absolute; top: 445px; left: 168px;">{{ $address }}</div>
      </div>
  </body>
</html>