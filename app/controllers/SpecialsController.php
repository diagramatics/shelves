<?php

class SpecialsController extends Controller {

  public function index() {
    $accountModel = $this->model("AccountModel");
    $isLoggedIn = $accountModel->isLoggedIn();

    $account = $this->database->getValue("Account", "", [
      ['email', '=', $_SESSION['email']]
    ]);
    $accountModel->parse($account);

    $specialsModel = array();
    $specialsRaw = $this->database->getValues("Promotion", "");
    $i = 0;
    foreach ($specialsRaw as $s) {
      $specialsModel[$i] = $this->model("SpecialsModel");
      $specialsModel[$i++]->parse($s);
    }

    // Check if the user is submitting subscription request
    if (isset($_GET['subscribe'])) {
      $this->subscribe($isLoggedIn);
    }

    $this->view('specials/index', [
      'title' => 'Specials',
      'logged' => $isLoggedIn,
      'subscribed' => $accountModel->getSpecialSub(),
      'specials' => $specialsModel
    ]);
  }

  private function subscribe($isLoggedIn) {
    if ($isLoggedIn) {
      $query = $this->database->updateValue("Account", [
        ['specialSub', '1']
      ],[
        ['email', '=', $_SESSION['email']]
      ]);

      if ($query) {
        Helpers::makeAlert('specialSub', "Awesome, you're now subscribed!");
      }
    }
    else {
      // TODO: Implement mail sending function
    }
  }

}

?>
