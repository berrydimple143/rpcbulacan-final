<div id="digital-id">
    <div id="did" style="border: 1px solid #aaa; margin-top: 5%; margin-left: auto; margin-right: auto; margin-bottom: 0; width: 765px; height: 485px; background-image: url(https://rpcbulacan.org/images/leni.png)">
      <div style="text-transform:uppercase; font-size: 12px; width: 210px; position: relative; top: 374px; left: 153px;">{{ $name }}</div>
      <div style="font-size: 13px; width: 210px; position: relative; top: 381px; left: 153px;">{{ $id }}</div>
      <div style="position: relative; top: 335px; left: 20px;">
          <img src="{{ $img }}" width="{{ $qrcode_size }}" height="{{ $qrcode_size }}">
      </div>
      <div style="text-transform:uppercase; width: 210px; font-size: 12px; position: relative; top: 305px; left: 165px;">{{ $address }}</div>
      <div style="font-size: 10px; position: relative; top: 400px; left: 215px;">
        <a wire:click="downloadnow" class="btn btn-lg btn-primary">{{ strtoupper('CLICK HERE TO DOWNLOAD ID') }}</a>
      </div>
    </div>
<div>