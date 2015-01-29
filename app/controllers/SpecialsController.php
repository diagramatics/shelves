<?php

class SpecialsController extends Controller {

  public function index() {
    $accountModel = $this->model("AccountModel");
    $isLoggedIn = $accountModel->isLoggedIn();

    if ($isLoggedIn) {
      $account = $this->database->getValue("Account", "", [
        ['email', '=', $_SESSION['email']]
      ]);
      $accountModel->parse($account);
    }

    // Check if the user is submitting subscription request
    if (isset($_GET['subscribe'])) {
      $this->subscribe($isLoggedIn);
    }

    $products = $this->database->getValues("Product", "");
    $productsFormat = array();
    // Format the whole array so it becomes an associative array with IDs as the indicator
    foreach ($products as $product) {
      $productsFormat[$product->prodID] = $product;
      $productsFormat[$product->prodID]->subCats = 0;
      $productsFormat[$product->prodID]->products = 0;
    }
    $products = $productsFormat;

    $specialsModel = array();
    $specialsRaw = $this->database->getValues("Promotion", "");
    if ($specialsRaw) {
      $i = 0;
      foreach ($specialsRaw as $s) {
        $specialsModel[$i] = $this->model("SpecialsModel");
        $specialsModel[$i]->parse($s);

        $productsRaw = $this->database->getValues("ProductPromotion", "", [
          ['promotionID', '=', $s->promotionID]
        ]);
        $specialsModel[$i]->linkProducts($productsRaw);
        $i++;
      }
    }

    $this->view('specials/index', [
      'title' => 'Specials',
      'logged' => $isLoggedIn,
      'subscribed' => $accountModel->getSpecialSub(),
      'products' => $products,
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
