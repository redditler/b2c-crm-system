<html>
<body>
<style type="text/css">
    body {
        font-family: Tahoma, sans-serif;
        padding: 0;
        margin: 0;
    }

    table {
        margin-left: 20px;
    }

    h2 {
        border-bottom: 1px dotted #ccc;
        font-weight: bold;
        margin-top: 40px;
    }
</style>

<table>
    <tr>
        <td>
            <h2>Новая заявка</h2>
        </td>
    </tr>
    <tr>
        <td>
            Имя: <strong>{{$leed_name}}</strong> <br/>
            Телефон: <strong>{{$leed_phone}}</strong> <br/>
            Город: <strong>{{$leed_region}}</strong> <br/>
            Метка: <strong>{{$leed_label}}</strong>
        </td>
    </tr>
</table>
</body>
</html>