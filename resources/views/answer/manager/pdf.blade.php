<?php
$language = \App\Models\Answer::LANGUAGE_UZ;
if (!empty($answers)) {
    $language = $answers[0]->language;
}
?>
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        * {
            font-family: 'DejaVu Sans', sans-serif;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        tr {
            border: 1px solid;
        }

        td {
            border: 1px solid;
        }

        th {
            border: 1px solid;
        }
    </style>
</head>

<body style="margin: 0; padding: 0;">
    <div class="container-fluid  p-0">
    <div class="row">
        <div class="col-md-12">
            <h4 class="text-center" style="margin-bottom: 40px">
                @if($language == \App\Models\Answer::LANGUAGE_UZ)
                    BUYURTMA MA'LUMOTLARI
                @endif
                @if($language == \App\Models\Answer::LANGUAGE_RU)
                        ЗАПРОСИТЬ ИНФОРМАЦИЮ
                @endif
            </h4>
        </div>
    </div>

    @foreach($images_src as $image_src)
        <img src="{{ $image_src }}"
             style="width: 40%; margin-bottom: 15px; margin-left: 50px;">
    @endforeach

    <table>
        <tr>
            <th style="width: 10%; padding: 5px 10px;">№</th>
            <th style="width: 45%; padding: 5px 10px;">{{ $language == \App\Models\Answer::LANGUAGE_RU ? 'Вопрос' : 'Savol' }}</th>
            <th style="width: 45%; padding: 5px 10px;">{{ $language == \App\Models\Answer::LANGUAGE_RU ? 'Ответ' : 'Javob' }}</th>
        </tr>
        @php $i = 2; @endphp
        @foreach($answers as $answer)
            @if($loop->iteration == 1)
                <tr>
                    <th style="padding: 5px 10px;">{{ 1 }}</th>
                    <td style="padding: 5px 10px;">
                        @if($language == \App\Models\Answer::LANGUAGE_UZ)
                            Filial:
                        @endif
                        @if($language == \App\Models\Answer::LANGUAGE_RU)
                            Филиал:
                        @endif
                    </td>
                    <td style="padding: 5px 10px;">
                        {{ $language == \App\Models\Answer::LANGUAGE_RU ? $answer->region->name_ru : $answer->region->name_uz }}
                    </td>
                </tr>
            @endif
            @if($answer->question->type != \App\Models\Question::TYPE_PHOTO)
                <tr>
                    <th style="padding: 5px 10px;">{{ $i++ }}</th>
                    <td style="padding: 5px 10px;">{{ $language == \App\Models\Answer::LANGUAGE_RU ? $answer->question->pdf_title_ru : $answer->question->pdf_title_uz }}</td>
                    <td style="padding: 5px 10px;">{{ $answer->text }}</td>
                </tr>
            @endif
        @endforeach
    </table>

</div>
</body>

</html>
