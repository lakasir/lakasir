import StockModal from "@/components/Product/Stock/Modal";
import { Card } from "@/ui/Card";
import { Layout } from "@/ui/Layout";
import { Modal } from "@/ui/Modals";
import { NextPage } from "next";
import Image from "next/image";
import { useState } from "react";

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
                id={m.id}
                onClick={() => {
                  setShow({ stock: true });
                  setId(m.id);
                }}
              />
            ))}
          </div>
        </div>
        <Modal onClose={(status) => setShow({ stock: status })} open={show.stock}>
          <StockModal id={id} />
        </Modal>
      </div>
    </Layout>
  );
};

export default StockManagemenet;
