@extends('layouts.app')
@section('title', 'Manual Backup Recovery')
@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0 font-weight-bold">
                                Manual Backup
                            </h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="index.html">Home</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="#">Backup</a></li>
                                    <li class="breadcrumb-item">Manual Backup</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <div class="row">
                    <div class="col-12">
                        <p>Create new backups or upload existing ones</p>
                    </div>
                </div>

                <section id="floating-label-layouts">
                    <div class="row match-height">

                        <div class="col-lg-6 col-md-12 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title"><i class="feather icon-mail"></i> Take Backup Now</h4>
                                    <!-- <p><small>Create a new backup of your system data from the server</small></p> -->
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <form class="form form-vertical">
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="location11">Backup Type</label>
                                                            <select class="custom-select form-control" id="location11"
                                                                name="location">
                                                                <option value="new-york">Select Backup Type</option>
                                                                <option value="database">Database Only (Local Download) </option>
                                                                <option value="warranty-pdfs">Warranty PDF's (Local Download)</option>
                                                                <!--<option value="wasabi_local">Wasabi + Local Download (Full Version)</option>-->
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <button type="button" id="startBackup"
                                                            class="btn btn-primary mr-1 mb-1">Submit</button>
                                                        <button type="reset"
                                                            class="btn btn-outline-warning mr-1 mb-1">Reset</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                         <div class="col-lg-6 col-md-12 col-12">
                            <section id="nav-filled">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="card overflow-hidden">
                                            <div class="card-header">
                                                <h4 class="card-title"><i class="feather icon-download-cloud"></i>Backup Taking Progress
                                                </h4>
                                                <p><small>Output</small></p>
                                            </div>
                                            <div class="card-content">
                                                <div class="card-body">

                                                  <div id="backupProgress" style="background:#111;color:#0f0;padding:10px;height:350px;overflow-y:scroll;font-family:monospace;"></div>

                                                  
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div> 
                       
                    </div>
                </section>


            </div>


        </div>
    </div>
    <!-- END: Content-->

   <script>
        let eventSource = null;

        document.getElementById('startBackup').addEventListener('click', function () {
            const type = document.getElementById('location11').value;

            if (!type || type === 'new-york') {
                alert('Please select backup type');
                return;
            }

            // close previous EventSource if exists
            if (eventSource) {
                eventSource.close();
                eventSource = null;
            }

            const logBox = document.getElementById('backupProgress');
            logBox.innerHTML = "Starting...\n";

            fetch("{{ route('backup.run') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ backupType: type })
            })
                .then(res => res.json())
                .then(data => {
                    if (!data.stream_url) {
                        logBox.innerHTML += "\nError starting backup.";
                        return;
                    }

                    eventSource = new EventSource(data.stream_url);

                    eventSource.onmessage = function (e) {
                        if (!e.data) return;

                        const line = document.createElement('div');
                        line.textContent = e.data;

                        if (e.data.includes('Warning')) {
                            line.style.color = '#ffa500';
                        } else if (e.data.includes('❌')) {
                            line.style.color = '#ff0000';
                        } else {
                            line.style.color = '#0f0';
                        }

                        if (e.data.startsWith('DOWNLOAD: ')) {
                            const url = e.data.replace('DOWNLOAD: ', '').trim();
                            const a = document.createElement('a');
                            a.href = url;
                            a.download = '';
                            document.body.appendChild(a);
                            a.click();
                            a.remove();
                        }

                        logBox.appendChild(line);
                        logBox.scrollTop = logBox.scrollHeight;
                    };

                    eventSource.addEventListener('done', function (e) {
                        logBox.innerHTML += "\n\n✔ Backup Completed\n";
                        if (eventSource) {
                            eventSource.close();
                            eventSource = null;
                        }
                    });

                    eventSource.onerror = function (err) {
                        console.error('SSE error', err);
                        logBox.innerHTML += "\n\nConnection error. Stopping stream.\n";
                        if (eventSource) {
                            eventSource.close();
                            eventSource = null;
                        }
                    };
                })
                .catch(err => {
                    console.error(err);
                    logBox.innerHTML += "\nRequest failed.";
                });
        });
    </script>

@endsection