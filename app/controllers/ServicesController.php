<?php

class ServicesController extends \BaseController {

    public function __construct()
    {
        $this->beforeFilter('auth', array('except' => array('show', 'showSubject')));
        $this->beforeFilter('pass_expired', array('except' => array('show', 'showSubject')));
    }
    /**
     * Show index of servicing
     * @return [View] [views/servicing.blade.php]
     */
    public function show()
    {
        $services = Services::orderBy('priority', 'DESC')->get();
        return View::make('servicing')
                    ->with('services', $services);
    }

    /**
     * The following methods are for an Admin to edit a Location
     */

    public function index() {
        $requiredPermissions = ['manage_service'];
        if(!parent::checkPermissions($requiredPermissions))
        {
            return Redirect::back()
                            ->with('alert-class', 'error')
                            ->with('flash-message', 'You do not have the required permissions to do that!');
        }
        $services = Services::orderBy('created_at', 'DESC')->get();

        return View::make('admin.manage.services')
                ->with('services', $services);
    }

    public function create() {
        $requiredPermissions = ['add_service'];
        if(!parent::checkPermissions($requiredPermissions))
        {
            return Redirect::back()
                            ->with('alert-class', 'error')
                            ->with('flash-message', 'You do not have the required permissions to do that!');
        }
        return View::make('admin.add.service');
    }

    public function store() {
        $requiredPermissions = ['add_service'];
        if(!parent::checkPermissions($requiredPermissions))
        {
            return Redirect::back()
                            ->with('alert-class', 'error')
                            ->with('flash-message', 'You do not have the required permissions to do that!');
        }
        $data = Input::all();

        if($data['service-subject'] == '' || $data['service-info'] == '') {
            return Redirect::to('/admin/services/create')
                            ->with('alert-class', 'error')
                            ->with('flash-message', 'Please fill out all fields to add a service!')
                            ->withInput();

        } elseif($data['priority'] == "select_priority") {
            return Redirect::to('/admin/services/create')
                            ->with('alert-class', 'error')
                            ->with('flash-message', 'Please select a priority!')
                            ->withInput();
        } else {
            $service = new Services;
            $service->subject = $data['service-subject'];
            $service->info = $data['service-info'];
            $service->priority = $data['priority'];
            $service->created_at = Carbon::now();
            $service->updated_at = Carbon::now();
            $service->save();

            $log = new Logs();
            $log->user_id = Auth::user()->id;
            $log->action = "Created the service <b>" . $data['service-subject'] . "</b>";
            $log->save();

            return Redirect::to('/admin/services')
                ->with('alert-class', 'success')
                ->with('flash-message', 'Service successfully created!');
        }
    }

    public function edit($id) {
        $requiredPermissions = ['edit_service'];
        if(!parent::checkPermissions($requiredPermissions))
        {
            return Redirect::back()
                            ->with('alert-class', 'error')
                            ->with('flash-message', 'You do not have the required permissions to do that!');
        }
        if($service = Services::find($id))
        {
            return View::make('admin.edit.service')
                    ->with('service', $service);
        } else {
            // Product doesn't exits (IE: User enters a number in the URL)
            return Redirect::to('/admin/services')
                            ->with('alert-class', 'error')
                            ->with('flash-message', 'The service you requested does\'nt exist!');
        }
    }

    public function update($id) {
        $requiredPermissions = ['edit_service'];
        if(!parent::checkPermissions($requiredPermissions))
        {
            return Redirect::back()
                            ->with('alert-class', 'error')
                            ->with('flash-message', 'You do not have the required permissions to do that!');
        }
        $data = Input::all();
        $service  = Services::find($id);

        if($data['service-subject'] != '' || $data['service-info'] != '')
        {
            // Update priority if it is set, otherwise don't update the priority.
            if($data['priority'] != 'select_priority')
            {
                $service->priority = $data['priority'];
            }

                $service->subject = $data['service-subject'];
                $service->info = $data['service-info'];
                $service->updated_at = Carbon::now();
                $service->save();

                $log = new Logs();
                $log->user_id = Auth::user()->id;
                $log->action = "Updated the service <b>" . $data['service-subject'] . "</b>";
                $log->save();

                return Redirect::to('/admin/services')
                                ->with('flash-message', 'Service has been successfully updated!')
                                ->with('alert-class', 'success');

        } else {
            return Redirect::to('/admin/services/' . $service->id . '/edit')
                            ->with('flash-message', 'Please fill out all fields to update a service!')
                            ->with('alert-class', 'error');
        }
    }

    public function destroy($id) {
        $requiredPermissions = ['delete_service'];
        if(!parent::checkPermissions($requiredPermissions))
        {
            return Redirect::back()
                            ->with('alert-class', 'error')
                            ->with('flash-message', 'You do not have the required permissions to do that!');
        }
        $service = Services::find($id);
        $oldName = $service->subject;
        $service->delete();

        $log = new Logs();
        $log->user_id = Auth::user()->id;
        $log->action = "Deleted the service <b>" . $oldName . "</b>";
        $log->save();
        return Redirect::to('/admin/services')
                        ->with('alert-class', 'success')
                        ->with('flash-message', 'Service <b>' . $oldName . '</b> has been deleted!');
    }

}