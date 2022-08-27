import { PencilIcon, ShoppingBagIcon } from "@heroicons/react/outline";
import { UserIcon } from "@heroicons/react/solid";
import { NextPage } from "next";
import Link from "next/link";
import Layout from "../../../components/Ui/Layout";

const About: NextPage = () => {
  return (
    <Layout
      title="About"
      back
      background="[#FF6600]"
      textColor="white"
      append={
        <div>
          <div className="w-screen h-96 bg-lakasir-primary flex justify-center items-center">
            <div>
              <div className="w-40 h-40 bg-white rounded-full flex items-center justify-center">
                <ShoppingBagIcon className="text-gray-300 w-24 h-24" />
              </div>
              <p className="text-center mt-5 text-2xl text-white font-semibold">
                Shop Name
              </p>
            </div>
            <Link href="/menu/about/edit">
              <a className="relative -top-10 left-3">
                <div className="absolute">
                  <PencilIcon className="text-white w-7 h-7" />
                </div>
              </a>
            </Link>
          </div>
        </div>
      }
    >
      <>
        <div className="my-7 space-y-5">
          <ul>
            <li>Name</li>
            <li className="font-semibold">Shopw Name</li>
          </ul>
          <ul>
            <li>Bussiness Type</li>
            <li className="font-semibold">Bakul</li>
          </ul>
          <ul>
            <li>Owner's Name</li>
            <li className="font-semibold">Robikin</li>
          </ul>
          <ul>
            <li>Location</li>
            <li className="font-semibold">
              Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam
            </li>
          </ul>
          <ul>
            <li>Currency</li>
            <li className="font-semibold">IDR</li>
          </ul>
        </div>
      </>
    </Layout>
  );
};

export default About;
