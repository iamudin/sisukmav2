<html>
    <body style="background:transparent;">
        <div style="width:1000px;height: 1000px;margin:0 auto;">
            {{QrCode::size(1000)->generate(route('survei.form',[base64_encode($skpd->id),base64_encode($layanan)]))}}

        </div>

    </body>
</html>

