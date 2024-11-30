const categories = [
  {
    id: "1c2a3b4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d",
    name: "Technology",
    parent: null,
  },
  {
    id: "2c3d4e5f-6a7b-8c9d-0e1f-2a3b4c5d6e7f",
    name: "Software Development",
    parent: "1c2a3b4d-5e6f-7a8b-9c0d-1e2f3a4b5c6d",
  },
  {
    id: "3d4e5f6a-7b8c-9d0e-1f2a-3b4c5d6e7f8a",
    name: "Hardware Engineering 2",
    parent: "2c3d4e5f-6a7b-8c9d-0e1f-2a3b4c5d6e7f",
  },
  {
    id: "3d4e5f6a-7b8c-9d0e-1f2a-3b4c5d6e7f82",
    name: "Hardware Engineering 3",
    parent: "3d4e5f6a-7b8c-9d0e-1f2a-3b4c5d6e7f8a",
  },
  {
    id: "4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b",
    name: "Education",
    parent: null,
  },
  {
    id: "5f6a7b8c-9d0e-1f2a-3b4c-5d6e7f8a9b0c",
    name: "Higher Education",
    parent: "4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b",
  },
  {
    id: "6a7b8c9d-0e1f-2a3b-4c5d-6e7f8a9b0c1d",
    name: "K-12 Education",
    parent: "4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b",
  },
  {
    id: "7b8c9d0e-1f2a-3b4c-5d6e-7f8a9b0c1d2e",
    name: "Health & Wellness",
    parent: null,
  },
  {
    id: "8c9d0e1f-2a3b-4c5d-6e7f-8a9b0c1d2e3f",
    name: "Fitness & Nutrition",
    parent: "7b8c9d0e-1f2a-3b4c-5d6e-7f8a9b0c1d2e",
  },
  {
    id: "9d0e1f2a-3b4c-5d6e-7f8a-9b0c1d2e3f4a",
    name: "Mental Health",
    parent: "7b8c9d0e-1f2a-3b4c-5d6e-7f8a9b0c1d2e",
  },
  {
    id: "0e1f2a3b-4c5d-6e7f-8a9b-0c1d2e3f4a5b",
    name: "Arts & Entertainment",
    parent: null,
  },
  {
    id: "1f2a3b4c-5d6e-7f8a-9b0c-1d2e3f4a5b6c",
    name: "Visual Arts",
    parent: "0e1f2a3b-4c5d-6e7f-8a9b-0c1d2e3f4a5b",
  },
  {
    id: "2a3b4c5d-6e7f-8a9b-0c1d-2e3f4a5b6c7d",
    name: "Performing Arts",
    parent: "0e1f2a3b-4c5d-6e7f-8a9b-0c1d2e3f4a5b",
  },
  {
    id: "3b4c5d6e-7f8a-9b0c-1d2e-3f4a5b6c7d8e",
    name: "Science & Nature",
    parent: null,
  },
  {
    id: "4c5d6e7f-8a9b-0c1d-2e3f-4a5b6c7d8e9f",
    name: "Biology",
    parent: "3b4c5d6e-7f8a-9b0c-1d2e-3f4a5b6c7d8e",
  },
  {
    id: "5d6e7f8a-9b0c-1d2e-3f4a-5b6c7d8e9f0a",
    name: "Physics",
    parent: "3b4c5d6e-7f8a-9b0c-1d2e-3f4a5b6c7d8e",
  },
  {
    id: "6e7f8a9b-0c1d-2e3f-4a5b-6c7d8e9f0a1b",
    name: "Food & Cooking",
    parent: null,
  },
  {
    id: "7f8a9b0c-1d2e-3f4a-5b6c-7d8e9f0a1b2c",
    name: "Recipes",
    parent: "6e7f8a9b-0c1d-2e3f-4a5b-6c7d8e9f0a1b",
  },
  {
    id: "8a9b0c1d-2e3f-4a5b-6c7d-8e9f0a1b2c3d",
    name: "Culinary Techniques",
    parent: "6e7f8a9b-0c1d-2e3f-4a5b-6c7d8e9f0a1b",
  },
  {
    id: "9b0c1d2e-3f4a-5b6c-7d8e-9f0a1b2c3d4e",
    name: "Travel & Tourism",
    parent: null,
  },
  {
    id: "0c1d2e3f-4a5b-6c7d-8e9f-0a1b2c3d4e5f",
    name: "Destinations",
    parent: "9b0c1d2e-3f4a-5b6c-7d8e-9f0a1b2c3d4e",
  },
  {
    id: "1d2e3f4a-5b6c-7d8e-9f0a-1b2c3d4e5f6a",
    name: "Travel Tips",
    parent: "9b0c1d2e-3f4a-5b6c-7d8e-9f0a1b2c3d4e",
  },
];

const courses = [
  {
    course_id: "L373349028",
    title: "Diversity and Inclusion for a Better Business",
    description:
      "Diversity can seem like a difficult concept to incorporate into the culture of a business. Leaders often view diversity initiatives as important but see them as secondary to the day-to-day operations of a successful business. You may ask yourself, where and how do I start? In this course, we’ll look at many strategies that can help jumpstart diversity and inclusion initiatives. Through these initiatives, we can build stronger relationships that improve the overall business environment and how it functions. These relationships can drive stability, sustainability, and profitability for years to come.  ",
    image_preview:
      "https://cdn0.knowledgecity.com/opencontent/courses/previews/L373349028/800--v112240.jpg",
    category_id: "3d4e5f6a-7b8c-9d0e-1f2a-3b4c5d6e7f8a",
  },
  {
    course_id: "L373371072",
    title: "Leadership for Identity Diversity",
    description:
      "As a leader, people of many different backgrounds will look to you for guidance and security in the workplace. The individual identities within a workplace can include individuals from different racial and ethnic backgrounds, individuals with different gender and sexual identities, and individuals with different disabilities.  One of the goals of a leader is to create a safe and inclusive environment for all employees. When creating an inclusive environment, it’s important to be aware of who you're creating it for and what their individual needs are. Recognizing individuality and implementing inclusion practices benefit everyone and improve your business’s culture.",
    image_preview:
      "https://cdn0.knowledgecity.com/opencontent/courses/previews/L373371072/800--v112239.jpg",
    category_id: "3d4e5f6a-7b8c-9d0e-1f2a-3b4c5d6e7f8a",
  },
  {
    course_id: "L373324687",
    title: "Applying Yourself to Diverse and Inclusive Leadership",
    description:
      "Improving diversity in the workplace requires strategic planning and mindful consideration from everyone involved because inclusion in the workplace is a team effort. When a leader is a participant in change rather than a director, the culture is able to transform with them. Strategies such as improved communication, modeling positivity and adaptability, and building relationships can help make the transition smoother. Effectively changing the culture of a business requires commitment and determination, which is why it’s important to know of leadership strategies that you can use to help you build and maintain a sustainable culture of diversity and equity.\n\n",
    image_preview:
      "https://cdn0.knowledgecity.com/opencontent/courses/previews/L373324687/800--v112241.jpg",
    category_id: "3d4e5f6a-7b8c-9d0e-1f2a-3b4c5d6e7f82",
  },
  {
    course_id: "L373312762",
    title: "Finance and Accounting Basics for Nonfinancial Executives",
    description:
      "Financial knowledge is vital to an executive’s role in a business, but the systems within a business can be extremely complex. Without a strong foundation of financial analytics, it can be difficult to interpret, report, or even understand a business’s financial standing. A lack of understanding can impede your ability to make educated decisions. By understanding where the data comes from and how accounting operates, you can manage your business with greater efficiency and interpret business systems more accurately. ",
    image_preview:
      "https://cdn0.knowledgecity.com/opencontent/courses/previews/L373312762/800--v112243.jpg",
    category_id: "4e5f6a7b-8c9d-0e1f-2a3b-4c5d6e7f8a9b",
  },
  {
    course_id: "L373319845",
    title: "Financial Statements and Reporting for Nonfinancial Executives",
    description:
      "Financial statements are a critical part of attracting investors. Financial reports like income statements are the hard proof of how your business is doing. Properly interpreting these statements can provide a stronger understanding of your business’s performance. This can also assist your company when acquiring new investments and making strategic business decisions. Your reliable and precise numbers may encourage shareholders and investors to feel more confident when working with you. ",
    image_preview:
      "https://cdn0.knowledgecity.com/opencontent/courses/previews/L373319845/800--v112244.jpg",
    category_id: "5f6a7b8c-9d0e-1f2a-3b4c-5d6e7f8a9b0c",
  },
  {
    course_id: "L373327593",
    title: "Financial Planning and Analysis for Nonfinancial Executives",
    description:
      "With constant market fluctuation and an unpredictable supply chain, sometimes it can be difficult to project where your business will be tomorrow. That’s where financial forecasting comes in. The data you have today can be used in various ratios and equations to create helpful financial estimates for your business. You can also use different aspects of financial and managerial accounting to better present your finances to potential and existing stakeholders. Streamlined financial reporting, planning, and analysis techniques can improve your business’s competitive strategy. ",
    image_preview:
      "https://cdn0.knowledgecity.com/opencontent/courses/previews/L373327593/800--v112246.jpg",
    category_id: "7b8c9d0e-1f2a-3b4c-5d6e-7f8a9b0c1d2e",
  },
  {
    course_id: "L373395597",
    title: "Valuation for Nonfinancial Executives",
    description:
      "Investments always involve a bit of risk, but you can lower that risk by analyzing your company’s current and future value. There are many options when it comes to funding a business or a project. Funding can be acquired through both debt and equity, as well as working capital. Learning the inner workings of project and relative valuation can help improve your investment decision-making skills and understand which projects will bring the best results.",
    image_preview:
      "https://cdn0.knowledgecity.com/opencontent/courses/previews/L373395597/800--v112241.jpg",
    category_id: "1f2a3b4c-5d6e-7f8a-9b0c-1d2e3f4a5b6c",
  },
  {
    course_id: "L373337574",
    title: "Defining Cross-Cultural Leadership",
    description:
      "The modern business landscape is noticeably globalized. People from many countries and cultures work together, whether in-person or remotely. You might work in an environment like this yourself, or you likely will in the future. That’s why it’s critical for you, as a leader, to have the necessary skills to navigate cultural differences within your company. Otherwise, you might not know how best to leverage your employees’ skills. So how can you do this? How can you become a cross-cultural leader?",
    image_preview:
      "https://cdn0.knowledgecity.com/opencontent/courses/previews/L373337574/800--v112262.jpg",
    category_id: "8a9b0c1d-2e3f-4a5b-6c7d-8e9f0a1b2c3d",
  },
];
