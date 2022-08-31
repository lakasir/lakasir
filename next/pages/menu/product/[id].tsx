import StockModal from "@/components/Product/Stock/Modal";
import { FloatingActionButton } from "@/ui/Buttons";
import { Modal } from "@/ui/Modals";
import { NextPage } from "next";
import Image from "next/image";
import { useRouter } from "next/router";
import { useState } from "react";

const Detail: NextPage = () => {
  const router = useRouter();
  const { id } = router.query;
  const [openModal, setOpenModal] = useState(false);

  return (
    <div className="h-screen absolute">
      <div className="w-screen h-72 overflow-hidden">
        <Image
          src={"/assets/products/product-image.jpg"}
          layout="responsive"
          width={"100%"}
          height={"100%"}
        />
      </div>
      <div className="w-full rounded-t-2xl relative -top-5 bg-white px-3 py-3">
        <div className="w-24 h-2 bg-gray-200 rounded-lg mx-auto"></div>
        <div className="grid gap-y-12">
          <div className="flex justify-between">
            <div>
              <p className="text-2xl">Product Name</p>
              <p className="text-sm font-light">Stock 200</p>
            </div>
            <p className="text-sm font-light place-self-center">
              Rp. 50.000,00
            </p>
          </div>
          <div>
            <p className="text-2xl">Details</p>
            <table className="table-auto border-spacing-10">
              <thead>
                <tr>
                  <th className="w-52"></th>
                </tr>
              </thead>
              <tbody className="leading-[1.75]">
                <tr>
                  <td>Initial Price</td>
                  <td>Rp. 50.000,00</td>
                </tr>
                <tr>
                  <td>Type</td>
                  <td>Product</td>
                </tr>
                <tr>
                  <td>Unit</td>
                  <td>KG</td>
                </tr>
                <tr>
                  <td>Category</td>
                  <td>UMUM</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div>
            <p className="text-2xl">Attributes</p>
          </div>
        </div>
      </div>
        <Modal onClose={setOpenModal} open={openModal}>
          <StockModal id={(id != undefined ? +id : undefined)} />
        </Modal>
      <div className="w-11/12 mx-auto">
        <FloatingActionButton
          action={`/menu/product/edit/${id}`}
          title="Edit Product"
          dismissable
          options={[
            {
              label: "Edit Property",
              icon: (
                <Image
                  src={"/assets/icons/Edit Property.svg"}
                  width={30}
                  height={30}
                />
              ),
              onClick: () => {},
            },
            {
              label: "Stock",
              icon: (
                <Image
                  src={"/assets/icons/Add to Collection.svg"}
                  width={30}
                  height={30}
                />
              ),
              onClick: () => setOpenModal(true),
            },
            {
              label: "Delete",
              icon: (
                <Image
                  src={"/assets/icons/Delete.svg"}
                  width={30}
                  height={30}
                />
              ),
              confirmable: true,
              onClick: () => {console.log("OK")},
            },
          ]}
        />
      </div>
    </div>
  );
};

export default Detail;
