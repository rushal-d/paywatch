<?php

namespace App\Http\Controllers;

use App\FiscalYearModel;
use App\Http\Requests\PublicHolidayRequest;
use App\PublicHoliday;
use App\Repositories\CasteRepository;
use App\Repositories\PublicHolidayRepository;
use App\Repositories\ReligionRepository;
use App\Repositories\SystemOfficeMastRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class PublicHolidayController extends Controller
{
    /**
     * @var PublicHolidayRepository
     */
    private $publicHolidayRepository;
    /**
     * @var SystemOfficeMastRepository
     */
    private $systemOfficeMastRepository;
    /**
     * @var ReligionRepository
     */
    private $religionRepository;
    /**
     * @var CasteRepository
     */
    private $casteRepository;

    /**
     * PublicHolidayController constructor.
     * @param PublicHolidayRepository $publicHolidayRepository
     * @param ReligionRepository $religionRepository
     * @param SystemOfficeMastRepository $systemOfficeMastRepository
     * @param CasteRepository $casteRepository
     */
    public function __construct(
        PublicHolidayRepository $publicHolidayRepository,
        ReligionRepository $religionRepository,
        SystemOfficeMastRepository $systemOfficeMastRepository,
        CasteRepository $casteRepository
    )
    {
        $this->publicHolidayRepository = $publicHolidayRepository;
        $this->systemOfficeMastRepository = $systemOfficeMastRepository;
        $this->religionRepository = $religionRepository;
        $this->casteRepository = $casteRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $records_per_page = ($request->has('rpp')) ? $request->input('rpp') : config('constants.records_per_page');
        $search_term = $request->search;

        $publicHolidays = PublicHoliday::query();

        if (!empty($branch_id = $request->branch_id))
            $publicHolidays->where('branch_id', $branch_id);

        if (!empty($religion_id = $request->religion_id))
            $publicHolidays->whereHas('religions', function ($query) use ($religion_id) {
                $query->where('religions.id', $religion_id);
            });

        if (!empty($caste_id = $request->caste_id))
            $publicHolidays->whereHas('castes', function ($query) use ($caste_id) {
                $query->where('castes.id', $caste_id);
            });

        if (!empty($gender = $request->gender))
            $publicHolidays->where('gender', $gender);

        $publicHolidays = $publicHolidays->search($search_term)->paginate($records_per_page);
        $branches = $this->systemOfficeMastRepository->retrieveAllBranchList();
        $genders = $this->publicHolidayRepository->getGenders();
        $religions = $this->religionRepository->getAll()->pluck('religion_name', 'id');
        $castes = $this->casteRepository->getAll()->pluck('caste_name', 'id');
        $records_per_page_options = config('constants.records_per_page_options');

        return view('publicholiday.index',
            compact('publicHolidays', 'records_per_page_options', 'genders', 'branches', 'religions', 'castes', 'records_per_page'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $genders = $this->publicHolidayRepository->getGenders();
        $branches = $this->systemOfficeMastRepository->retrieveAllBranchListWithoutDefaultPlaceholder();
        $religions = $this->religionRepository->getAll()->pluck('religion_name', 'id');
        $castes = $this->casteRepository->getAll()->pluck('caste_name', 'id');

        $fiscalyear = FiscalYearModel::ascOrder()->pluck('fiscal_code', 'id');
        $status_options = config('constants.status_options');

        $publicHoliday = new PublicHoliday;
        $buttonName = 'Save';
        $selectedFiscalYear = FiscalYearModel::isActiveFiscalYear()->value('id');

        return view('publicholiday.create', compact('selectedFiscalYear', 'genders', 'branches', 'castes', 'religions', 'publicHoliday', 'buttonName', 'fiscalyear', 'status_options'));
    }

    /**
     * @param PublicHolidayRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PublicHolidayRequest $request)
    {
        $saveStatus = $this->publicHolidayRepository->saveMany($request);
        $status = ($saveStatus) ? 'success' : 'error';
        $mesg = $this->publicHolidayRepository->getSaveStatusMessage($saveStatus);
        return redirect()->route('public-holiday-create')
            ->with('flash', ['status' => $status, 'mesg' => $mesg]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $genders = $this->publicHolidayRepository->getGenders();
        $branches = $this->systemOfficeMastRepository->retrieveAllBranchList();
        $religions = $this->religionRepository->getAll()->pluck('religion_name', 'id');
        $castes = $this->casteRepository->getAll()->pluck('caste_name', 'id');
        $buttonName = 'Update';


        $fiscalyear = FiscalYearModel::ascOrder()->pluck('fiscal_code', 'id');
        $status_options = config('constants.status_options');

        $publicHoliday = PublicHoliday::where('id', $id)->first();
        $selectedFiscalYear = FiscalYearModel::isActiveFiscalYear()->value('id');
        if (!empty($publicHoliday->fiscal)) {
            $selectedFiscalYear = $publicHoliday->fiscal->fy_year;
        }

        return view('publicholiday.edit', compact('fiscalyear', 'selectedFiscalYear', 'status_options', 'publicHoliday', 'genders', 'castes', 'branches', 'religions', 'buttonName'));
    }

    /**
     * @param PublicHolidayRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PublicHolidayRequest $request, $id)
//    public function update(Request $request, $id)
    {
        $request->merge(['id' => $id]);
        $saveStatus = $this->publicHolidayRepository->store($request);
        $status = ($saveStatus) ? 'success' : 'error';
        $mesg = $this->publicHolidayRepository->getSaveStatusMessage($saveStatus);
        return redirect()->route('public-holiday-edit', [$id])
            ->with('flash', ['status' => $status, 'mesg' => $mesg]);
    }

    public function destroy(Request $request)
    {
        if (!empty($request->id)) {
            $publicHoliday = PublicHoliday::find($request->id);
            if ($publicHoliday->delete()) {
                $success = true;
            }
            if ($success) {
                echo 'Successfully Deleted';
            } else {
                echo "Error deleting!";
            }
        } else {
            echo "Error deleting!";
        }
    }
}
