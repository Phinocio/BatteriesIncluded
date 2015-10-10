<?php

class FAQController extends \BaseController {

    public function __construct()
    {
        $this->beforeFilter('auth', array('except' => array('show')));
        $this->beforeFilter('pass_expired', array('except' => array('show')));
    }


    /**
     * Show FAQs on the main /faq page. This is the only method that isn't part of the Admin interface.
     */
    public function show()
    {
        // Get all FAQs and order them by the set priority.
        $faqs = FAQ::orderBy('priority', 'DESC')->get();

        return View::make('faq')
            ->with('faqs', $faqs);
    }


    /**
     * The following methods are for an Admin to add/edit/delete a FAQ
     */
    public function index()
    {
        // Get all FAQs and order them by the set priority.
        $faqs = FAQ::orderBy('priority', 'DESC')->get();

        return View::make('admin.manage.faqs')
            ->with('faqs', $faqs);
    }

    public function create() {
        return View::make('admin.add.faq');
    }

    public function store() {
        $data = Input::all();

        if($data['faq-question'] == '' || $data['faq-answer'] == '') {
            return Redirect::to('/admin/faqs/create')
                            ->with('alert-class', 'error')
                            ->with('flash-message', 'Please fill out all fields to add a FAQ!')
                            ->withInput();

        } elseif($data['priority'] == "select_priority") {
            return Redirect::to('/admin/faqs/create')
                            ->with('alert-class', 'error')
                            ->with('flash-message', 'Please select a priority!')
                            ->withInput();
        } else {
            $FAQ = new FAQ;
            $FAQ->question = $data['faq-question'];
            $FAQ->answer = $data['faq-answer'];
            $FAQ->priority = $data['priority'];
            $FAQ->created_at = Carbon::now();
            $FAQ->updated_at = Carbon::now();
            $FAQ->save();
            return Redirect::to('/admin/faqs')
                ->with('alert-class', 'success')
                ->with('flash-message', 'FAQ successfully created!');
        }

    }

    public function edit($id)
    {
        if($faq = FAQ::find($id))
        {
            return View::make('admin.edit.faq')
                    ->with('faq', $faq);
        } else {
            // FAQ doesn't exits (IE: User enters a number in the URL)
            return Redirect::to('/admin/faqs')
                            ->with('alert-class', 'error')
                            ->with('flash-message', 'The FAQ you requested does\'nt exist!');
        }
    }

    public function update($id)
    {
        $data = Input::all();
        $FAQ  = FAQ::find($id);

        if($data['faq-question'] != '' || $data['faq-answer'] != '')
        {
            // Update priority if it is set, otherwise don't update the priority.
            if($data['priority'] != 'select_priority')
            {
                $FAQ->priority = $data['priority'];
            }

                $FAQ->question = $data['faq-question'];
                $FAQ->answer = $data['faq-answer'];
                $FAQ->updated_at = Carbon::now();
                $FAQ->save();
                return Redirect::to('/admin/faqs')
                                ->with('flash-message', 'FAQ has been successfully updated!')
                                ->with('alert-class', 'success');

        } else {
            return Redirect::to($_ENV['URL'] . '/admin/faqs/' . $faq->id . '/edit')
                            ->with('flash-message', 'Please fill out all fields to update a FAQ!')
                            ->with('alert-class', 'error');
        }
    }

    public function destroy($id) {
        $faq = FAQ::find($id);
        $oldName = $faq->question;
        $faq->delete();
        return Redirect::to('/admin/faqs')
                        ->with('alert-class', 'success')
                        ->with('flash-message', 'FAQ <b>' . $oldName . '</b> has been deleted!');
    }
}