<?php 

  namespace App\Http\Requests\Auth\RoleClaims;

  // This class is an Enum instance where you should define a Claim
  // a Claim will then be assigned to Roles
  abstract class Claims {
    const ProductIndex = 'product.index';
    const ProductEdit = 'product.edit';
    const ProductStore = 'product.store';
    const ProductDestroy = 'product.destroy';

    const CategoryIndex = 'category.index';
    const CategoryEdit = 'category.edit';
    const CategoryStore = 'category.store';
    const CategoryDestroy = 'category.destroy';

    const SellIndex = 'sell.index';
    const SellProducts = 'sell.products';
    const SellAddToReceipt = 'sell.addToReceipt';
    const SellSaveReceipt = 'sell.saveReceipt';
    const SellPrintReceipt = 'sell.printReceipt';

    const ReceiptIndex = 'receipt.index';
    const ReceiptDestroy = 'receipt.destroy';

    const ReportIndex = 'report.index';
    const ReportChartReport = 'report.chartReport';
  }
