import sys
import joblib
import numpy as np

if len(sys.argv) < 10:
    print("Usage: python classify.py model_file feature1 feature2 ... feature8")
    sys.exit(1)

# Load model and input data
model_file = sys.argv[1]
features = list(map(float, sys.argv[2:]))
data_to_classify = np.array(features).reshape(1, -1)

# Load trained model
model = joblib.load(model_file)

# Predict
prediction = model.predict(data_to_classify)[0]

# Optional: Map to label if using label encoding
labels = {
    0: "not_recom",
    1: "priority",
    2: "recommend",
    3: "spec_recommend",
    4: "very-recommend"
}

print(labels.get(prediction, "Unknown"))
