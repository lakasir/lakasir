import { TrashIcon } from "@heroicons/react/outline";
import { NextPage } from "next";
import Image from "next/image";
import { useRouter } from "next/router";
import { useState } from "react";
import StockModal from "../../../../components/Product/Stock/Modal";
import Button from "../../../../components/Ui/Buttons/Button";
import Card from "../../../../components/Ui/Card/Card";
import Checkbox from "../../../../components/Ui/Fields/Checkbox";
import Form from "../../../../components/Ui/Fields/Form";
import Input from "../../../../components/Ui/Fields/Input";
import Layout from "../../../../components/Ui/Layout";
import Modal from "../../../../components/Ui/Modals";

interface IMenuInterface {
  label: string;
  description: string;
  sub_description: string;
  image: JSX.Element;
  id: number;
}

const product: IMenuInterface = {
  id: 1,
  label: "Product A",
  description: "100 stock",
  sub_description: "Rp. 5.000,00 - Rp. 5.200,00",
  image: (
    <Image
      src={"/assets/products/product-image.jpg"}
      width="100%"
      height="100%"
      className="rounded-lg"
    />
  ),
};
interface ShowActionInterface {
  stock: boolean;
}

const StockHistory: NextPage = () => {
  const router = useRouter();
  const { history } = router.query;
  const productId = history;

  const [show, setShow] = useState<ShowActionInterface>({
    stock: false,
  });

  return (
    <Layout title="Stock Product A" back={true}>
      <div>
        <div>
          <div className="py-3 space-y-8 mb-24">
            <Card
              label={product.label}
              description={product.description}
              sub_description={product.sub_description}
              image={product.image}
              class={{ confirmable: { confirm: "py-7", cancel: "py-7" } }}
              id={product.id}
            />
            <div className="h-28 w-full bg-gray-300 rounded-lg py-3 px-4 flex justify-between">
              <div>
                <p className="font-semibold">The last initial price</p>
                <Button
                  className="bg-gray-100 text-black px-5 py-2 rounded-[15px]"
                  onClick={() => setShow({ stock: true })}
                >
                  Add / Reduce Stock
                </Button>
              </div>
              <p className="text-sm">Rp. 5.000,00</p>
            </div>
            <div>
              <div className="flex justify-between">
                <div>
                  <p className="text-sm font-light">15 may 2022</p>
                  <p className="font-semibold">5</p>
                </div>
                <TrashIcon className="w-7 h-7 text-red-500 place-self-center" />
              </div>
              <hr className="border-[1.5px] rounded-lg"/>
            </div>
            <div>
              <div className="flex justify-between">
                <div>
                  <p className="text-sm font-light">15 may 2022</p>
                  <p className="font-semibold">5</p>
                </div>
                <TrashIcon className="w-7 h-7 text-red-500 place-self-center" />
              </div>
              <hr className="border-[1.5px] rounded-lg"/>
            </div>
            <div>
              <div className="flex justify-between">
                <div>
                  <p className="text-sm font-light">15 may 2022</p>
                  <p className="font-semibold">5</p>
                </div>
                <TrashIcon className="w-7 h-7 text-red-500 place-self-center" />
              </div>
              <hr className="border-[1.5px] rounded-lg"/>
            </div>
            <div>
              <div className="flex justify-between">
                <div>
                  <p className="text-sm font-light">15 may 2022</p>
                  <p className="font-semibold">5</p>
                </div>
                <TrashIcon className="w-7 h-7 text-red-500 place-self-center" />
              </div>
              <hr className="border-[1.5px] rounded-lg"/>
            </div>
          </div>
        </div>
        <Modal onClose={setShow} open={show.stock}>
          <StockModal />
        </Modal>
      </div>
    </Layout>
  );
};
export default StockHistory;
