{{-- FILE: resources\views\inventory.blade.php --}}
@extends('layouts.app')
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
                                 Backup Inventory
                             </h2>
                             <div class="breadcrumb-wrapper col-12">
                                 <ol class="breadcrumb">
                                     <li class="breadcrumb-item">
                                         <a href="index.html">Home</a>
                                     </li>
                                     <li class="breadcrumb-item"><a href="#">Users</a></li>
                                     <li class="breadcrumb-item">Users List</li>
                                 </ol>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="content-body">
                 <div class="row">
                     <div class="col-12">
                         <p>Manage User Accounts</p>
                     </div>
                 </div>
                 <!-- Data list view starts -->
                 <section id="data-list-view" class="data-list-view-header">
                     <div class="card">
                         <div class="card-content">
                             <div class="card-body card-dashboard">
                                 <!-- 🔍 Filter Form -->
                                 <form method="GET" action="{{ route('inventory') }}"
                                     class="row mb-4 g-2 align-items-end">
                                     <div class="col-md-4">
                                         <label for="search" class="form-label">Search Backup Name</label>
                                         <input type="text" name="search" id="search" value="{{ $search }}"
                                             class="form-control" placeholder="e.g. backup_2025_10_30.zip">
                                     </div>

                                     <div class="col-md-3">
                                         <label for="date" class="form-label">Filter by Date</label>
                                         <input type="date" name="date" id="date" value="{{ $date }}"
                                             class="form-control">
                                     </div>

                                     <div class="col-md-3">
                                         <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                                     </div>

                                     <div class="col-md-2">
                                         <a href="{{ route('inventory') }}" class="btn btn-secondary w-100">Reset</a>
                                     </div>
                                 </form>
                                 <!-- DataTable starts -->
                                 <div class="table-responsive">
                                     <table class="table">
                                         <thead>
                                             <tr>
                                                 <th></th>
                                                 <th>Backup Name</th>
                                                 <th>Date Created</th>
                                                 <th>Size</th>
                                                 <th>Status</th>
                                                 <th>Restore</th>
                                                 <th>Actions</th>
                                             </tr>
                                         </thead>
                                         <tbody>
                                             @forelse($files as $index => $file)
                                                 <tr>
                                                     <td>{{ $files->firstItem() + $index }}</td>
                                                     <td>{{ $file['name'] }}</td>
                                                     <td>{{ $file['last_modified'] }}</td>
                                                     <td>{{ $file['size'] }}</td>
                                                     <td><span class="badge badge-success">Available</span></td>
                                                     <td>
                                                         {{-- Livewire restore component handles batched download & progress --}}
                                                         <livewire:restore-backup :file="$file['name']" :wire:key="$file['name']" />
                                                     </td>
                                                     <td>
                                                         <button class="btn btn-sm btn-primary download-btn"
                                                             data-file="{{ urlencode($file['name']) }}">
                                                             <i class="feather icon-download"></i> Download
                                                         </button>
                                                     </td>
                                                 </tr>
                                             @empty
                                                 <tr>
                                                     <td colspan="7" class="text-center">No backups found.</td>
                                                 </tr>
                                             @endforelse
                                         </tbody>
                                     </table>

                                     <!-- 📄 Pagination -->

                                     <nav>
                                         {{ $files->onEachSide(2)->links('pagination::bootstrap-5') }}
                                     </nav>

                                 </div>

                             </div>
                         </div>
                     </div>
                 </section>

                 <div id="download-progress-container" style="position: fixed; bottom: 20px; right: 20px; width: 300px;display:none; ">
                     <div class="card text-white bg-gradient-info p-3">
                        
                           <h6 class="text-white">Downloading in progress...</h6>
                             <div class="progress progress-bar-default progress-xl">
                                 <div id="download-progress-bar" class="progress-bar progress-bar-striped"
                                     role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
                                     style="width:0%;"></div>
                             </div>
                         

                     </div>
                 </div>

                 <!-- Data list view end -->
             </div>


         </div>
     </div>
     <!-- END: Content-->
 @endsection

 @push('scripts')
     <script>
         document.addEventListener('DOMContentLoaded', function() {
             document.querySelectorAll('.download-btn').forEach(button => {
                 button.addEventListener('click', async function() {
                     const file = this.dataset.file;
                     const url = `/inventory/download/${file}`;
                     const progressContainer = document.getElementById(
                         'download-progress-container');
                     const progressBar = document.getElementById('download-progress-bar');
                     const cancelBtn = document.getElementById('cancel-download');

                     progressContainer.style.display = 'block';
                     progressBar.style.width = '0%';
                     progressBar.textContent = 'Preparing...';

                     try {
                         // Step 1: Ask Laravel for a signed URL
                         const response = await axios.get(url);
                         const downloadUrl = response.data.url;

                         if (!downloadUrl) throw new Error('No signed URL returned');

                         // Step 2: Start actual download (browser handles it directly)
                         const a = document.createElement('a');
                         a.href = downloadUrl;
                         a.download = decodeURIComponent(file.split('/').pop());
                         document.body.appendChild(a);
                         a.click();
                         a.remove();

                         progressBar.style.width = '100%';
                         progressBar.textContent = 'Download started (via Wasabi)';
                         setTimeout(() => (progressContainer.style.display = 'none'), 3000);
                     } catch (error) {
                         console.error(error);
                         progressBar.textContent = 'Failed to start download';
                         alert('Failed: ' + (error.response?.data?.error || error.message));
                         setTimeout(() => (progressContainer.style.display = 'none'), 3000);
                     }
                 });
             });
         });
     </script>
 @endpush
