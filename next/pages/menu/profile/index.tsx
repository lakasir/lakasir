import { PencilIcon } from "@heroicons/react/outline";
import { UserIcon } from "@heroicons/react/solid";
import { NextPage } from "next";
import Link from "next/link";
import Layout from "../../../components/Ui/Layout";

const Profile: NextPage = () => {
  return (
    <Layout
      title="Profile"
      back
      background="[#FF6600]"
      textColor="white"
    >
      <>
        <div className="w-screen h-96 bg-lakasir-primary flex justify-center items-center -ml-[17px]">
          <div className="w-40 h-40 bg-white rounded-full flex items-center justify-center ml-9">
            <UserIcon className="text-gray-300 w-24 h-24" />
          </div>
          <Link href="/menu/profile/edit">
            <a>
              <PencilIcon className="text-white w-7 h-7 ml-3" />
            </a>
          </Link>
        </div>
        <div>
          <ul>
            <li>Name</li>
            <li>Profile Name</li>
          </ul>
        </div>
      </>
    </Layout>
  );
};

export default Profile;
