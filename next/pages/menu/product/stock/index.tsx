import { NextPage } from "next";
import Image from "next/image";
import { useState } from "react";
import StockModal from "../../../../components/Product/Stock/Modal";
import Card from "../../../../components/Ui/Card/Card";
import Layout from "../../../../components/Ui/Layout";
import Modal from "../../../../components/Ui/Modals";

interface IMenuInterface {
  label: string;
  description: string;
  sub_description: string;
  image: JSX.Element;
  id: number;
}

const product: IMenuInterface[] = [
  {
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
  },
  {
    id: 2,
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
  },
  {
    id: 3,
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
  },
];

interface ShowActionInterface {
  stock: boolean;
}

const StockManagemenet: NextPage = () => {
  const [show, setShow] = useState<ShowActionInterface>({
    stock: false,
  });

  const [id, setId] = useState(1);

  return (
    <Layout title="Stock Management" back={true}>
      <div>
        <div>
          <div className="py-3 space-y-8 mb-24">
            {product.map((m, index) => (
              <Card
                key={index}
                label={m.label}
                description={m.description}
                sub_description={m.sub_description}
                image={m.image}
                class={{ confirmable: { confirm: "py-7", cancel: "py-7" } }}
                id={m.id}
                onClick={() => {
                  setShow({ stock: true });
                  setId(m.id);
                }}
              />
            ))}
          </div>
        </div>
        <Modal onClose={setShow} open={show.stock}>
          <StockModal id={id} />
        </Modal>
      </div>
    </Layout>
  );
};

export default StockManagemenet;
