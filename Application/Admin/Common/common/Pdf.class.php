<?php
namespace Admin\Common\Common;

Class Pdf{
    public static function ExporPdf($name,$image)
    {
        import("DomPdf.autoload");
        $dompdf = new \Dompdf\src\Dompdf();

        $html ='<!doctype html>
        <html>
            <meta charset="UTF-8">
                <head>
                <style>
                body{
                    font-family: helvetica, Arial;
                    text-align: center;
                }
                </style>
                </head>
                <body>
                   <div>
                    <img src='.$image.' alt="">
                    <h1>'.$name.'</h1>
                    </div>
                </body>
        </html>';

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'landscape');

        $dompdf->render();
        $dompdf->stream();

    }
}