<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentCartConnector\Business\Cart;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;

/**
 * @deprecated Exists for Backward Compatibility reasons only.
 */
class ShipmentCartExpanderQuoteDataBCForMultiShipmentAdapter implements ShipmentCartExpanderQuoteDataBCForMultiShipmentAdapterInterface
{
    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function adapt(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if ($this->assertThatItemTransfersHaveShipmentAndShipmentMethodAndShipmentExpense($quoteTransfer)) {
            return $quoteTransfer;
        }

        if ($this->assertThatQuoteHasNoShipment($quoteTransfer)) {
            return $quoteTransfer;
        }

        if ($this->assertThatQuoteHasNoShipmentMethod($quoteTransfer)) {
            return $quoteTransfer;
        }

        $quoteExpenseTransfer = $this->findQuoteShipmentExpense($quoteTransfer);
        if ($quoteExpenseTransfer === null) {
            return $quoteTransfer;
        }

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($this->assertThatItemTransferHasShipmentWithShipmentMethodAndExpense($itemTransfer)) {
                continue;
            }

            $this->setItemTransferShipmentAndShipmentMethodAndShipmentExpenseForBC($itemTransfer, $quoteTransfer, $quoteExpenseTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function assertThatItemTransfersHaveShipmentAndShipmentMethodAndShipmentExpense(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getShipment() === null
                || $itemTransfer->getShipment()->getMethod() === null
                || $itemTransfer->getShipment()->getExpense() === null
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function assertThatQuoteHasNoShipment(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getShipment() === null;
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function assertThatQuoteHasNoShipmentMethod(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getShipment()->getMethod() === null;
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer|null
     */
    protected function findQuoteShipmentExpense(QuoteTransfer $quoteTransfer): ?ExpenseTransfer
    {
        foreach ($quoteTransfer->getExpenses() as $key => $expenseTransfer) {
            if ($expenseTransfer->getType() !== ShipmentConstants::SHIPMENT_EXPENSE_TYPE) {
                continue;
            }

            return $expenseTransfer;
        }

        return null;
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function assertThatItemTransferHasShipmentWithShipmentMethodAndExpense(ItemTransfer $itemTransfer): bool
    {
        return ($itemTransfer->getShipment() !== null
            && $itemTransfer->getShipment()->getMethod() !== null
            && $itemTransfer->getShipment()->getExpense() !== null
        );
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    protected function getShipmentTransferForBC(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): ShipmentTransfer
    {
        if ($itemTransfer->getShipment() !== null) {
            return $itemTransfer->getShipment();
        }

        return $quoteTransfer->getShipment();
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    protected function getShipmentMethodTransferForBC(ItemTransfer $itemTransfer, QuoteTransfer $quoteTransfer): ShipmentMethodTransfer
    {
        if ($itemTransfer->getShipment() !== null && $itemTransfer->getShipment()->getMethod() !== null) {
            return $itemTransfer->getShipment()->getMethod();
        }

        return $quoteTransfer->getShipment()->getMethod();
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ExpenseTransfer $quoteExpenseTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function getShipmentExpenseTransferForBC(ItemTransfer $itemTransfer, ExpenseTransfer $quoteExpenseTransfer): ExpenseTransfer
    {
        if ($itemTransfer->getShipment() !== null && $itemTransfer->getShipment()->getExpense() !== null) {
            return $itemTransfer->getShipment()->getExpense();
        }

        return $quoteExpenseTransfer;
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ExpenseTransfer $quoteExpenseTransfer
     *
     * @return void
     */
    protected function setItemTransferShipmentAndShipmentMethodAndShipmentExpenseForBC(
        ItemTransfer $itemTransfer,
        QuoteTransfer $quoteTransfer,
        ExpenseTransfer $quoteExpenseTransfer
    ): void {
        $shipmentTransfer = $this->getShipmentTransferForBC($itemTransfer, $quoteTransfer);
        $itemTransfer->setShipment($shipmentTransfer);

        $shipmentMethodTransfer = $this->getShipmentMethodTransferForBC($itemTransfer, $quoteTransfer);
        $shipmentExpenseTransfer = $this->getShipmentExpenseTransferForBC($itemTransfer, $quoteExpenseTransfer);
        $shipmentTransfer->setMethod($shipmentMethodTransfer)
            ->setExpense($shipmentExpenseTransfer);
    }
}