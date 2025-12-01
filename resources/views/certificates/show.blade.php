@php
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\Storage;

    $studentName = trim(($student->first_name ?? '').' '.($student->last_name ?? '')) ?: ($user->name ?? 'Student');
    $courseName  = $lesson->title;
    $orgName     = $lesson->organization ?? optional($lesson->organizationRelation ?? null)->name;
    $dateStr     = Carbon::parse($issuedAt)->format('F j, Y');

    $execName = $settings->exec_director_name ?? null;
    $sigUrl   = !empty($settings?->exec_director_signature_path) ? Storage::url($settings->exec_director_signature_path) : null;
    $logoUrl  = !empty($settings?->logo) ? asset('storage/'.$settings->logo) : asset('storage/app-images/mex-learning-logo.png');
@endphp
    <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificate - {{ $courseName }}</title>
    <style>
        @page { size: 11in 8.5in; margin: 0; }

        html, body { height:100%; margin:0; }
        body { background:#e9ecef; }

        .scale-wrap { position:relative; width:100%; padding:16px; box-sizing:border-box; }
        .scale-inner { width:1056px; height:816px; transform-origin:top left; } /* 11×8.5in @96dpi */

        /* Page */
        .sheet{
            width:11in; height:8.5in; padding:1in; box-sizing:border-box;
            position:relative; margin:0;
            box-shadow:0 2px 10px rgba(0,0,0,.1);
            font-family:"Times New Roman", Georgia, serif; color:#111; text-align:center;
            background:#fff;
        }
        .cert-bg{ position:absolute; inset:0; width:100%; height:100%; object-fit:cover; z-index:0;
            -webkit-print-color-adjust:exact; print-color-adjust:exact; }

        .cert-content, .footer { position:relative; z-index:1; }

        h1{ font-size:56pt; margin:0 0 .35em; color:#38537F; }
        .org{ font-size:16pt; color:#555; margin:0 0 1.2em; }
        .name{ font-size:32pt; font-weight:700; margin:.1in 0 .12in; color:#38537F; }
        .rule{ width:6in; height:2px; background:#bbb; margin:.1in auto .18in; }
        .line1{ font-size:14pt; margin:.05in 0; }
        .course{ font-size:16pt; font-weight:700; margin:.05in 0; color:#38537F; }
        .date{ font-size:14pt; margin:.05in 0 .25in; }
        .serial{ font-size:10pt; color:#333; }

        /* Footer layout: 3 columns, lines aligned */
        .footer{
            position:absolute; left:1.25in; right:1.25in; bottom:.85in;
            display:grid; grid-template-columns: 2.6in 1fr 2.6in; column-gap:.3in; align-items:end;
            font-size:11pt;
            padding-bottom: 10px;
        }
        .sig{
            /* 4 fixed rows: [above] [line] [label1] [label2] ensures same line height on both sides */
            display:grid; grid-template-rows: 1fr 2px auto auto;
            height:1.15in; text-align:center; align-items:end;
        }
        .sig-line{
            display:block;
            width: 2.6in;                 /* same visual width as before */
            height: 0.06in;               /* gives the stroke some print heft */
            margin: .10in auto .12in;     /* matches the left side */
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .sig__above{ align-self:end; }
        .sig__img{ max-height:.55in; object-fit:contain; display:block; margin:0 auto .08in; }
        .line{
            width:2.6in; height:2px; background:#222; margin:0 auto;   /* prints reliably */
        }
        .sig__name{ font-weight:700; }
        .sig__title{ font-size:10pt; color:#333; margin-top:.05in; }

        .sig--date .sig__above{ margin-bottom:.10in; } /* nudge date text above the line */

        .footer-logo{
            justify-self:center; align-self:end;
            max-height:1.6in; max-width:3.4in; object-fit:contain; margin-bottom:.15in;
        }

        .no-print{ display:none !important; }

        @media print{
            body{ background:#fff; }
            .scale-inner{ transform:none !important; }
            .scale-wrap{ padding:0 !important; height:auto !important; }
            .sheet{ box-shadow:none; }
            .line{ background:#222 !important; } /* ensure strong line in print */
            .sig-line{ height: 0.06in; }
        }
    </style>
</head>
<body>
<div class="scale-wrap">
    <div class="scale-inner" id="cert-scale">
        <div class="sheet" id="cert-root">
            <img class="cert-bg" src="{{ asset('storage/app-images/certificate.svg') }}" alt="" aria-hidden="true">

            <div class="cert-content">
                <h1>Certificate of Completion</h1>
                <div class="org">
                    <em>In Recognition of Significant Achievement</em><br><br>
                    <strong>THIS CERTIFICATE IS PROUDLY <br> PRESENTED TO</strong>
                </div>

                <div class="name">{{ $studentName }}</div>
                <div class="rule"></div>

                <div class="line1">For the successful completion of the course</div>
                <div class="course">“{{ $courseName }}”</div>
                <div class="date">on {{ $dateStr }}.</div>

                <div class="serial">Certificate No. {{ $serial }}</div>
            </div>

            <div class="footer">
                <!-- LEFT: Executive Director -->
                <div class="sig">
                    <div class="sig__above">
                        @if($sigUrl)
                            <img class="sig__img" src="{{ $sigUrl }}" alt="Executive Director Signature">
                        @endif
                    </div>
                    <svg class="sig-line" viewBox="0 0 100 2" preserveAspectRatio="none" aria-hidden="true">
                        <line x1="0" y1="1" x2="100" y2="1" stroke="#222" stroke-width=".25"/>
                    </svg>
                    <div class="sig__name">{{ $execName ?: 'Authorized Signature' }}</div>
                    <div class="sig__title">{{ $execName ? 'Executive Director' : '' }}</div>
                </div>

                <!-- CENTER: Logo -->
                <img class="footer-logo" src="{{ $logoUrl }}" alt="Logo">

                <!-- RIGHT: Date block -->
                <div class="sig sig--date">
                    <div class="sig__above">{{ $dateStr }}</div>
                    <svg class="sig-line" viewBox="0 0 100 2" preserveAspectRatio="none" aria-hidden="true">
                        <line x1="0" y1="1" x2="100" y2="1" stroke="#222" stroke-width=".25"/>
                    </svg>
                    <div class="sig__title">Date</div>
                    <div><br></div><!-- spacer row to match left side height -->
                </div>
            </div>
        </div>
    </div>
</div>

@if(empty($forPdf))
    <div class="no-print" style="position: fixed; top:12px; right:12px; display:flex; gap:.5rem; z-index:5;">
        <a href="{{ route('certificates.download', $lesson) }}" class="btn btn-primary" style="padding:.5rem .75rem; border:1px solid #333; text-decoration:none;">Download PDF</a>
        <button onclick="window.print()" class="btn" style="padding:.5rem .75rem; border:1px solid #333;">Print</button>
    </div>
@endif

<script>
    (function () {
        function fitCert(){
            var inner=document.getElementById('cert-scale'); if(!inner) return;
            var wrap=inner.parentElement, scale=wrap.clientWidth/inner.offsetWidth;
            inner.style.transform='scale('+scale+')';
            wrap.style.height=(inner.offsetHeight*scale+32)+'px';
        }
        window.addEventListener('load',fitCert);
        window.addEventListener('resize',fitCert);
    })();
</script>
</body>
</html>
