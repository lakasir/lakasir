import { useProduct } from "@/hooks/product";
import { IProductResponse } from "@/models/product";
import { Response } from "@/models/response";
import { FloatingActionButton } from "@/ui/Buttons";
import { Card } from "@/ui/Card";
import { Input } from "@/ui/Fields";
import { Layout } from "@/ui/Layout";
import { Modal } from "@/ui/Modals";
import { formatPrice } from "@/utils/helpers";
import { CameraIcon } from "@heroicons/react/outline";
import { PencilIcon, TrashIcon } from "@heroicons/react/solid";
import { NextPage } from "next";
import { useRouter } from "next/router";
import { useEffect, useState } from "react";

interface ShowActionInterface {
  delete?: boolean;
  option?: boolean;
  search?: boolean;
  confirm?: boolean;
  stock?: boolean;
}

const Product: NextPage = () => {
  const { getProduct, deleteProduct } = useProduct();
  const [productData, setProductData] = useState<IProductResponse[]>([]);
  const [show, setShow] = useState<ShowActionInterface>({
    delete: false,
    option: false,
    search: false,
    confirm: false,
    stock: false,
  });
  const loadData = async () => {
    const response = await getProduct();
    if (response) {
      const responseData = response as Response<IProductResponse[]>;
      setProductData(responseData.data);
    }
  };

  useEffect(() => {
    loadData();
  }, [productData]);
  console.log(productData);

  const router = useRouter();

  return (
    <Layout title="Product" back onClick={() => router.push("/menu")}>
      <div>
        <div>
          <div className="py-3 space-y-4 mb-24">
            {productData.map((m, index) => (
              <Card
                onClick={() => {
                  router.push(`/menu/product/${m.id}`);
                }}
                key={index}
                label={m.name}
                description={`${m.stock} stock`}
                sub_description={`${formatPrice(
                  m.initial_price
                )} - ${formatPrice(m.selling_price)}`}
                image={
                  <div className="flex-shrink-0">
                    {m.images.length > 0 ? (
                        <img
                        src={m.images[0].url}
                        width="100%"
                        height="100%"
                        />
                    ) : (
                      <CameraIcon className="h-10 w-10 text-white" />
                    )}
                  </div>
                }
                id={m.id}
                action={[
                  {
                    icon: <TrashIcon className="w-5 h-5" />,
                    label: "Delete",
                    confirmable: async (confirm) => {
                      if (confirm) {
                        await deleteProduct(m.id);
                        loadData();
                      }
                    },
                  },
                  {
                    icon: <PencilIcon className="w-5 h-5 space-x-0" />,
                    label: "Edit",
                    onClick: () => {
                      router.push(`/menu/product/edit/${m.id}`);
                    },
                  },
                ]}
              />
            ))}
          </div>
          <FloatingActionButton
            title="Add Product"
            action="/menu/product/add"
            dismissable
            options={[
              {
                label: "Search",
                icon: (
                  <img
                    src={"./../assets/icons/Search.svg"}
                    width="30"
                    height="30"
                  />
                ),
                onClick: () => setShow({ search: !show.search }),
              },
              {
                label: "Category",
                icon: (
                  <img
                    src={"./../assets/icons/Diversity.svg"}
                    width="30"
                    height="30"
                  />
                ),
                onClick: () => router.push("/menu/category"),
              },
              {
                label: "Stock",
                icon: (
                  <img
                    src={"./../assets/icons/Add to Collection.svg"}
                    width="30"
                    height="30"
                  />
                ),
                onClick: () => router.push("/menu/product/stock"),
              },
            ]}
          />
        </div>
        <Modal
          open={show.search != undefined ? show.search : false}
          onClose={(status) => setShow({ search: status })}
        >
          <div className="w-11/12 mx-auto">
            <Input
              name={"Search"}
              type={"text"}
              className="rounded-r-none border-r-0"
              append={
                <div
                  className="bg-gray-100 rounded-r-lg w-1/5 flex justify-center items-center cursor-pointer border-2 border-gray-300"
                  onClick={() => alert("ok")}
                >
                  <img src="./../assets/icons/Search-Red.svg" />
                </div>
              }
            />
          </div>
        </Modal>
      </div>
    </Layout>
  );
};

export default Product;
