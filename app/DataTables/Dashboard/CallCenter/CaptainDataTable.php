<?php
namespace App\DataTables\Dashboard\CallCenter;
use App\Models\Captain;
use App\DataTables\Base\BaseDataTable;
use Yajra\DataTables\EloquentDataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Utilities\Request as DataTableRequest;
class CaptainDataTable extends BaseDataTable {
    public function __construct(DataTableRequest $request) {
        parent::__construct(new Captain());
        $this->request = $request;
    }

    public function dataTable($query): EloquentDataTable {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (Captain $captain) {
                return view('dashboard.call-center.captains.btn.actions', compact('captain'));
            })


            ->addColumn('images', function (Captain $captain) {
                $personalPhoto = ['personal_avatar', 'id_photo_front', 'id_photo_back', 'criminal_record', 'captain_license_front', 'captain_license_back'];
                $carPhoto = ['car_license_front', 'car_license_back', 'car_front', 'car_back', 'car_right', 'car_left', 'car_inside'];
                $hasImages = $captain->images()->exists();
                $personalImagesCount = $captain->images()->where('type', 'personal')->count();
                $carImagesCount = $captain->images()->where('type', 'car')->count();
                $requiredPersonalPhotosCount = count($personalPhoto);
                $requiredCarPhotosCount = count($carPhoto);
                $missingPersonalPhotoCount = $requiredPersonalPhotosCount - $personalImagesCount;
                $missingCarPhotoCount = $requiredCarPhotosCount - $carImagesCount;
            
                $backgroundColor = '';
                if (!$hasImages) {
                    $backgroundColor = '#ffc107'; // warning for captain not have any media
                    $textColorClass = 'text-dark';
                } elseif ($missingPersonalPhotoCount > 0 || $missingCarPhotoCount > 0) {
                    $backgroundColor = '#ffc107'; // warning for captain not have any media
                    $textColorClass = 'text-dark';
                } else {
                    // التحقق من حالة الصور الموجودة
                    $rejectedImagesExist = $captain->images()->where('photo_status', 'rejected')->exists();
                    $allImagesActive = $captain->images()->where('photo_status', 'accept')->count() == ($personalImagesCount + $carImagesCount);
                    $notActiveImagesExist = $captain->images()->where('photo_status', 'not_active')->exists();
                    if ($allImagesActive) {
                        $backgroundColor = '#28a745'; // success
                        $textColorClass = 'text-white';
                    } elseif ($rejectedImagesExist) {
                        $backgroundColor = '#FF7F7F'; // if captain media have a reject photo
                        $textColorClass = 'text-white';
                    } elseif ($notActiveImagesExist) {
                        $backgroundColor = 'gray'; // لون رمادي
                        $textColorClass = 'text-white';
                    } else {
                        $backgroundColor = '#dc3545'; // لون خطر للصور المرفوضة
                        $textColorClass = 'text-white';
                    }
                }
                $personalMessage = "Personal: $personalImagesCount (Missing: $missingPersonalPhotoCount)";
                $carMessage = "Car: $carImagesCount (Missing: $missingCarPhotoCount)";
                $tdContent = '<span style="background-color: ' . $backgroundColor . '" class="' . $textColorClass . '">' . $personalMessage . '<br>' . $carMessage . '</span>';
                return $tdContent;
            })
            
            
            ->editColumn('created_at', function (Captain $captain) {
                return $this->formatBadge($this->formatDate($captain->created_at));
            })
            ->editColumn('updated_at', function (Captain $captain) {
                return $this->formatBadge($this->formatDate($captain->updated_at));
            })
            ->editColumn('name', function (Captain $captain) {
                return '<a href="'.route('CallCenterCaptains.show', $captain->profile->uuid).'">'.$captain->name.'</a>';
            })
            ->editColumn('status', function (Captain $captain) {
                return $this->formatStatus($captain->status);
            })
            ->editColumn('country_id', function (Captain $captain) {
                return $captain?->country?->name;
            })
            ->editColumn('callcenter', function (Captain $captain) {
                return $captain?->callcenter?->name;
            })
            
            /*->setRowClass(function ($captain) {
                $captainActivity = $captain->captainActivity;
                if ($captainActivity && isset($captainActivity->status_captain_work)) {
                    switch ($captainActivity->status_captain_work) {
                        case 'waiting':
                            return 'text-dark custom-bg-warning'; 
                        case 'block':
                            return 'text-white bg-danger';
                        case 'active':
                            return 'text-dark custom-bg-success';
                        default:
                            return 'secondary';
                    }
                } elseif($captainActivity === null) {
                    return 'text-white bg-secondary';
                } else {
                    return 'info';
                }
            })*/
            ->setRowClass(function ($captain) {
                $captainActivity = $captain->captainActivity;
                if ($captainActivity && isset($captainActivity->status_captain_work)) {
                    switch ($captainActivity->status_captain_work) {
                        case 'block':
                            return 'text-white bg-danger';
                    }
                }
                return '';
            })
            ->rawColumns(['action', 'created_at', 'updated_at','status', 'country_id', 'name','callcenter', 'images',]);
    }

    public function query() {
        return Captain::query()->with(['callcenter', 'images'])->whereCountryId(get_user_data()->country_id)->latest();
    }

    public function getColumns(): array {
        return [
            ['name' => 'id', 'data' => 'id', 'title' => '#', 'orderable' => false, 'searchable' => false,],
            ['name' => 'name', 'data' => 'name', 'title' => 'Name',],
            ['name' => 'email', 'data' => 'email', 'title' => 'Email', 'orderable' => false, 'searchable' => false,],
            ['name' => 'phone', 'data' => 'phone', 'title' => 'Phone'],
            ['name' => 'callcenter', 'data' => 'callcenter', 'title' => 'callcenter', 'orderable' => false, 'searchable' => false,],
            ['name' => 'country_id', 'data' => 'country_id', 'title' => 'Country', 'orderable' => false, 'searchable' => false,],
            ['name' => 'images', 'data' => 'images', 'title' => 'Images', 'orderable' => false, 'searchable' => false,],
            ['name' => 'status', 'data' => 'status', 'title' => 'Status', 'orderable' => false, 'searchable' => false,],
            ['name' => 'created_at', 'data' => 'created_at', 'title' => 'Created_at', 'orderable' => false, 'searchable' => false,],
            ['name' => 'updated_at', 'data' => 'updated_at', 'title' => 'Update_at', 'orderable' => false, 'searchable' => false,],
            ['name' => 'action', 'data' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false,],
        ];
    }
}
