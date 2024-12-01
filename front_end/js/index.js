const categoryList = document.getElementById("categories");
const courseList = document.getElementById("courses");
const homeButton = document.getElementById("home-button");
const toggleSidebarButton = document.getElementById("toggle-sidebar");
const BASE_SERVER_URL = "http://api.cc.localhost";

async function fetchCategories(id = null) {
    const newCategories = await (
        await fetch(`${BASE_SERVER_URL}/categories${id ? `/${id}` : ""}`)
    ).json();

    return newCategories;
}

async function fetchCourses(id = null, categoryId = null) {
    return await (
        await fetch(
            `${BASE_SERVER_URL}/courses${id ? `/${id}` : ""}${
                categoryId ? `?category_id=${categoryId}` : ""
            }`
        )
    ).json();
}

function buildCategoryTree(categories, parentId = "") {
    return categories
        .filter((category) => {
            const res = category.parent_id === parentId;
            return res;
        })
        .map((category) => {
            const childCategories = buildCategoryTree(categories, category.id);
            return { ...category, childCategories };
        });
}

function toggleActiveCategory(even) {
    document
        .querySelectorAll("#categories li")
        .forEach((el) => el.classList.remove("active-category"));

    event.currentTarget.classList.add("active-category");
}

function renderCategories(categoryTree, depth = 0) {
    if (depth >= 5) {
        return;
    }

    const ul = document.createElement("ul");
    ul.classList.add("category-list");

    categoryTree.forEach((c) => {
        const li = document.createElement("li");
        li.textContent = `${c.name} (${c.count_of_courses})`;

        li.addEventListener("click", (event) => {
            event.stopPropagation();
            toggleActiveCategory(event);
            filterCoursesByCategory(c.id, c.childCategories);
        });

        if (c.childCategories.length > 0) {
            const div = document.createElement("div");
            div.classList.add("collapse-category");

            const button = document.createElement("button");
            button.classList.add("collapse-btn");

            const image = document.createElement("img");
            image.src = "images/collapse-arrow.png";
            image.alt = "Collapse arrow";
            image.classList.add("collapse-img");
            button.appendChild(image);

            const newDiv = document.createElement("div");
            newDiv.style = `display: flex; gap: 0.25rem; align-items: center; padding-left: ${
                15 * depth
            }px`;
            newDiv.appendChild(button);
            newDiv.appendChild(li);
            div.appendChild(newDiv);

            const childrenUl = renderCategories(c.childCategories, depth + 1);
            div.appendChild(childrenUl);

            button.addEventListener("click", (event) => {
                event.stopPropagation();
                const isCollapsed = childrenUl.style.display === "none";
                childrenUl.style.display = isCollapsed ? "block" : "none";
                image.style.transform = isCollapsed
                    ? "rotate(0deg)"
                    : "rotate(-90deg)";
            });

            ul.appendChild(div);
        } else {
            li.style.paddingLeft = `${depth * 30}px`;
            ul.appendChild(li);
        }
    });

    return ul;
}

function filterCoursesByCategory(categoryId, childCategories) {
    const allCategoryIds = getAllCategoryIds(categoryId, childCategories);

    const filteredCourses = courses.filter((course) =>
        allCategoryIds.includes(course.category_id)
    );

    renderCourses(filteredCourses);
}

function getAllCategoryIds(categoryId, childCategories) {
    let ids = [categoryId];

    childCategories.forEach((child) => {
        ids = ids.concat(getAllCategoryIds(child.id, child.childCategories));
    });

    return ids;
}

function renderCourses(courseData) {
    courseList.innerHTML = "";

    if (courseData.length === 0) {
        const h3 = document.createElement("h3");
        h3.style = "min-height: 500px; display: grid; place-content: center";
        h3.innerText = "No courses found.";
        courseList.appendChild(h3);
        return;
    }

    courseData.forEach((course) => {
        const card = document.createElement("div");
        card.className = "course-card";
        card.innerHTML = `
      <img src="${course.preview}" alt="${
            course.name
        }"> <!-- Updated to 'preview' -->
      <span style="position: absolute; right: 15px; top: 5px; background-color: var(--primary-color); color: white; font-size: 20px; padding: 4px 6px;border-radius: var(--border-radius)">
        ${getRootCategoryName(course.category_id)}
      </span>
      <div>
        <h3>${course.name}</h3> <!-- Updated to 'name' -->
        <p>${course.description}</p>
        <span>Category: ${getCategoryName(course.category_id)}</span>
      </div>
    `;
        courseList.appendChild(card);
    });
}

function getCategoryName(categoryId) {
    const category = categories.find((cat) => cat.id === categoryId);
    return category ? category.name : "Unknown";
}

function getRootCategoryName(categoryId) {
    const category = categories.find((cat) => cat.id === categoryId);
    if (!category) {
        return "Unknown";
    }

    if (category.parent_id === "") {
        return category.name;
    }

    return getRootCategoryName(category.parent_id);
}

async function renderPage(categories, courses) {
    const categoryTree = buildCategoryTree(categories);
    const ul = renderCategories(categoryTree, 0);
    categoryList.appendChild(ul);
    renderCourses(courses);
}

// Initialize
const categoriesRes = await fetchCategories();
const categories = categoriesRes.data.categories;

const coursesRes = await fetchCourses();
const courses = coursesRes.data.courses;

renderPage(categories, courses);

toggleSidebarButton.addEventListener("click", () => {
    categoryList.classList.toggle("closed");
    toggleSidebarButton.querySelector("img").classList.toggle("flipped");
});

homeButton.addEventListener("click", (event) => {
    toggleActiveCategory(event);
    renderCourses(courses);
});
